<?php

namespace App\Livewire;

use App\Enums\QueueTicketPriority;
use App\Enums\QueueTicketStatus;
use App\Events\QueueTicketCalledEvent;
use App\Models\Queue;
use App\Models\QueueTicket;
use Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Log;

class QueueManager extends Component
{
    /* ------------------------ Constantes ------------------------ */
    private const PRIORITY_QUOTA = 2;    // 2 PRIORITY → 1 NORMAL
    private const LOCK_TTL = 5;    // seg.
    private const LOCK_WAIT = 3;    // seg.

    /* ------------------------ Propriedades ---------------------- */
    public Queue $queue;
    public $currentTicket;               // exibido na view


    public function mount(int $id): void
    {
        $this->queue = Auth::user()->supermarket->queues->findOrFail($id);
        $this->refreshCurrentTicket();   // usa sempre a model
    }

    /**
     * Sempre busca o ticket atual diretamente da model (fonte única da verdade).
     */
    public function refreshCurrentTicket(): void
    {
        $this->queue->refresh();               // garante dados recentes da relação
        $this->currentTicket = $this->queue->currentTicket();
    }

    /**
     * Chama próxima senha respeitando o rodízio 2×1.
     */
    public function callNextTicket(): void
    {
        $this->locked(function ($counterKey) {

            $priorityCalls = Cache::get($counterKey, 0);

            /* 1. PRIORITY (se ainda não bateu a cota) */
            if ($priorityCalls < self::PRIORITY_QUOTA &&
                ($ticket = $this->nextWaiting(QueueTicketPriority::PRIORITY))) {

                $this->callTicket($ticket);
                Cache::increment($counterKey);
                return;
            }

            /* 2. NORMAL */
            if ($ticket = $this->nextWaiting(QueueTicketPriority::NORMAL)) {
                $this->callTicket($ticket);
                Cache::put($counterKey, 0);       // zera cota
                return;
            }

            /* 3. PRIORITY de novo (quando não há NORMAL) */
            if ($ticket = $this->nextWaiting(QueueTicketPriority::PRIORITY)) {
                $this->callTicket($ticket);
                Cache::increment($counterKey);
            }
        });

        $this->refreshCurrentTicket();   // garante consistência externa
    }

    /**
     * Empacota lock Redis + transação em um helper para evitar repetição.
     */
    private function locked(callable $callback): void
    {
        $key = "queue:{$this->queue->id}:call_lock";
        $counterKey = "queue:{$this->queue->id}:priority_call_count";

        Cache::lock($key, self::LOCK_TTL)
            ->block(self::LOCK_WAIT, function () use ($callback, $counterKey) {

                DB::transaction(function () use ($callback, $counterKey) {
                    $callback($counterKey);        // executa a lógica específica
                });
            });
    }

    /**
     * Próximo ticket WAITING de uma prioridade (com lock pessimista).
     */
    private function nextWaiting(QueueTicketPriority $priority): ?QueueTicket
    {
        return $this->queue->queueTickets()
            ->where('status', QueueTicketStatus::WAITING)
            ->where('priority', $priority)
            ->orderBy('created_at')
            ->lockForUpdate()
            ->first();
    }

    /**
     * Chama um ticket, atualiza status e dispara eventos.
     */
    private function callTicket(QueueTicket $ticket): void
    {
        $ticket->update([
            'status' => QueueTicketStatus::CALLED,
            'called_at' => now(),
        ]);

        /* Notificação individual (SMS, e‑mail, push…) */


        /* Broadcast ao painel / TVs */
        $this->announceTicket($ticket);

        Log::info("Ticket {$ticket->ticket_number} chamado na fila {$ticket->queue_id}");
    }

    /**
     * Dispara tudo o que deve acontecer quando UM ticket
     * é colocado em atendimento (som, painel TV, push, etc.).
     */
    private function announceTicket(QueueTicket $ticket): void
    {
        broadcast(new QueueTicketCalledEvent($ticket));
    }

    /**
     * Volta para a senha anterior.
     */
    public function callPreviousTicket(): void
    {
        if (!$this->currentTicket?->called_at) {
            return; // nada para voltar
        }

        $pivotCalledAt = $this->currentTicket->called_at;
        $pivotPriority = $this->currentTicket->priority;

        $this->locked(function ($counterKey) use ($pivotCalledAt, $pivotPriority) {

            /* 1. Reverte o ticket que estava em display */
            $this->revertTicket($this->currentTicket);

            /* 1b. Ajusta quota se era PRIORITY */
            if ($pivotPriority === QueueTicketPriority::PRIORITY) {
                Cache::decrement($counterKey);
                if (Cache::get($counterKey) < 0) {
                    Cache::put($counterKey, 0);
                }
            }

            /* 2. Busca o ticket CALLED imediatamente anterior */
            $previous = $this->queue->queueTickets()
                ->where('status', QueueTicketStatus::CALLED)
                ->where('called_at', '<', $pivotCalledAt)
                ->orderByDesc('called_at')
                ->lockForUpdate()
                ->first();

            /* 3. Se achou, dispara anúncio novamente */
            if ($previous) {
                $this->announceTicket($previous);
            }
        });

        /* 4. Atualiza ponteiro na model & tela */
        $this->refreshCurrentTicket();
    }

    /**
     * Centraliza a reversão de um ticket,
     * útil para "voltar" ou corrigir chamadas.
     */
    private function revertTicket(QueueTicket $ticket): void
    {
        $ticket->update([
            'status' => QueueTicketStatus::WAITING,
            'called_at' => null,
        ]);

        // (opcional) notifique o cliente sobre a reversão
        // $ticket->client?->notify(new TicketReverted($ticket));
    }

    public function render()
    {
        return view('livewire.queue-manager');
    }
}
