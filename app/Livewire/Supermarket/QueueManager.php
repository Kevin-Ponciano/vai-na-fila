<?php

namespace App\Livewire\Supermarket;

use App\Enums\QueueTicketPriority;
use App\Enums\QueueTicketStatus;
use App\Events\QueueTicketCalledEvent;
use App\Events\QueueTicketUpdatedEvent;
use App\Jobs\ExpireTicketJob;
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
    private const LOCK_TTL = 5;          // seg.
    private const LOCK_WAIT = 3;         // seg.
    private const EXPIRATION_TIME = 0.1;   // min.

    /* ------------------------ Propriedades ---------------------- */
    public Queue $queue;
    public $currentTicket;
    public $hasNextTicket;
    public $hasPreviousTicket;

    /* ------------------- Métodos do Ciclo de Vida --------------- */

    public function mount(int $id): void
    {
        $this->queue = Auth::user()->supermarket->queues->findOrFail($id);

        $this->refreshCurrentTicket();
    }

    /**
     * Sempre busca o ticket atual diretamente da model (fonte única da verdade).
     */
    public function refreshCurrentTicket($ticket = null): void
    {
        if ($ticket) {
            $this->currentTicket = $ticket;
        } else {
            $this->queue->refresh();
            $this->currentTicket = $this->queue->currentTicket() ?: $this->queue->lastCalledTicket();
        }
        //$this->hasNextTicket = (bool)$this->nextWaiting();
        //$this->hasPreviousTicket = (bool)$this->findPreviousTicket($this->currentTicket?->called_at);
    }

    /* --------------------- Ações Públicas ----------------------- */

    public function render()
    {
        return view('livewire.queue-manager');
    }

    public function getListeners(): array
    {
        return [
            "echo-private:queue.{$this->queue->id},.ticket.expired" => 'onExpiredTicket',
        ];
    }

    public function onExpiredTicket($event): void
    {
        $ticketId = $event['ticket_id'];
        $sessionId = $event['session_id'];


        if ($ticketId === $this->currentTicket->id
            && $this->currentTicket->status === QueueTicketStatus::CALLING->value
            && $sessionId === session()->getId()) {
            $this->callNextTicket();
        }
    }

    /**
     * Chama próxima senha respeitando o rodízio 2×1.
     */
    public function callNextTicket(): void
    {
        $this->executeWithLock(function ($counterKey) {
            $this->handleCurrentTicketCompletion();
            $this->attemptToCallNextTicket($counterKey);
        });

        $this->refreshCurrentTicket();
    }

    /**
     * Executa uma operação com lock Redis para evitar race conditions.
     */
    private function executeWithLock(callable $callback): void
    {
        $key = "queue:{$this->queue->id}:call_lock";
        $counterKey = "queue:{$this->queue->id}:priority_call_count";

        Cache::lock($key, self::LOCK_TTL)
            ->block(self::LOCK_WAIT, function () use ($callback, $counterKey) {
                DB::transaction(function () use ($callback, $counterKey) {
                    $callback($counterKey);
                });
            });
    }

    /* ------------------ Gerenciamento de Tickets ----------------- */

    /**
     * Finaliza o ticket atual antes de chamar o próximo.
     */
    private function handleCurrentTicketCompletion(): void
    {
        if (!$this->currentTicket
            || $this->currentTicket->status === QueueTicketStatus::CALLED->value
            || $this->currentTicket->status === QueueTicketStatus::EXPIRED->value) {
            return;
        }

        if ($this->currentTicket->status === QueueTicketStatus::CALLING->value) {
            $this->currentTicket->update([
                'status' => QueueTicketStatus::EXPIRED->value,
            ]);
        } else {
            $this->currentTicket->update([
                'status' => QueueTicketStatus::CALLED->value,
            ]);
        }
        $this->informeClient($this->currentTicket);
    }

    private function informeClient(QueueTicket $ticket): void
    {
        broadcast(new QueueTicketUpdatedEvent($ticket));
    }

    /**
     * Tenta chamar o próximo ticket com base nas regras de prioridade.
     */
    private function attemptToCallNextTicket($counterKey): void
    {
        $priorityCalls = Cache::get($counterKey, 0);

        /* 1. PRIORITY (se ainda não bateu a cota) */
        if ($priorityCalls < self::PRIORITY_QUOTA &&
            ($ticket = $this->nextWaiting(QueueTicketPriority::PRIORITY->value))) {
            $this->callTicket($ticket);
            Cache::increment($counterKey);
            return;
        }

        /* 2. NORMAL */
        if ($ticket = $this->nextWaiting(QueueTicketPriority::NORMAL->value)) {
            $this->callTicket($ticket);
            Cache::put($counterKey, 0); // zera cota
            return;
        }

        /* 3. PRIORITY de novo (quando não há NORMAL) */
        if ($ticket = $this->nextWaiting(QueueTicketPriority::PRIORITY->value)) {
            $this->callTicket($ticket);
            Cache::increment($counterKey);
        }
    }

    /**
     * Próximo ticket WAITING de uma prioridade (com lock pessimista).
     */
    private function nextWaiting($priority = null): ?QueueTicket
    {
        return $this->queue->queueTickets()
            ->where('status', QueueTicketStatus::WAITING->value)
            ->when($priority, function ($query) use ($priority) {
                return $query->where('priority', $priority);
            })
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
            'status' => QueueTicketStatus::CALLING->value,
            'called_at' => now(),
        ]);

        $this->announceTicket($ticket);
        $this->informeClient($ticket);

        Log::info("Ticket {$ticket->ticket_number} chamado na fila {$ticket->queue_id}");
    }

    /**
     * Dispara tudo o que deve acontecer quando UM ticket
     * é colocado em atendimento (som, painel TV, push, etc.).
     */
    private function announceTicket(QueueTicket $ticket): void
    {
        QueueTicketCalledEvent::dispatch($ticket);
        $this->dispatchExpireJob($ticket);
    }

    private function dispatchExpireJob(QueueTicket $ticket): void
    {
        $validationCode = now()->timestamp;
        $sessionId = session()->getId();

        Cache::put("ticket:{$ticket->id}:meta", [
            'validate_code' => $validationCode,
            'session_id' => $sessionId,
        ], now()->addMinutes(self::EXPIRATION_TIME + 1));

        ExpireTicketJob::dispatch(
            ticketId: $ticket->id,
            validateCode: $validationCode,
            sessionId: $sessionId
        )->delay(now()->addMinutes(self::EXPIRATION_TIME));
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

        $this->executeWithLock(function ($counterKey) use ($pivotCalledAt, $pivotPriority) {
            /* 1. Reverte o ticket que estava em display */
            $oldTicket = $this->currentTicket;
            //$this->revertTicket($this->currentTicket);

            /* 1b. Ajusta quota se era PRIORITY */
            if ($pivotPriority === QueueTicketPriority::PRIORITY->value) {
                $this->decrementPriorityCounter($counterKey);
            }

            /* 2. Busca o ticket CALLED imediatamente anterior */
            $previous = $this->findPreviousTicket($pivotCalledAt);

            /* 3. Se achou, dispara anúncio novamente */
            if ($previous) {
                $this->revertTicket($oldTicket);
                $this->callTicket($previous);
            }

            /* 4. Atualiza ponteiro na model & tela */
            $this->refreshCurrentTicket($previous);
        });
    }

    /**
     * Decrementa o contador de prioridade com segurança.
     */
    private function decrementPriorityCounter($counterKey): void
    {
        Cache::decrement($counterKey);
        if (Cache::get($counterKey) < 0) {
            Cache::put($counterKey, 0);
        }
    }

    /**
     * Busca o ticket CALLED imediatamente anterior.
     */
    private function findPreviousTicket($pivotCalledAt): ?QueueTicket
    {
        return $this->queue->queueTickets()
            ->where(function ($q) {
                $q->where('status', QueueTicketStatus::CALLED->value)
                    ->orExpiredStillValid();
            })
            ->where('called_at', '<', $pivotCalledAt)
            ->orderByDesc('called_at')
            ->lockForUpdate()
            ->first();

    }

    /**
     * Centraliza a reversão de um ticket,
     * útil para "voltar" ou corrigir chamadas.
     */
    private function revertTicket(QueueTicket $ticket): void
    {
        if ($ticket->status === QueueTicketStatus::IN_SERVICE->value) {
            $ticket->update([
                'status' => QueueTicketStatus::CALLED->value,
            ]);
        } elseif ($ticket->status !== QueueTicketStatus::CALLED->value) {
            $ticket->update([
                'status' => QueueTicketStatus::WAITING->value,
                'called_at' => null,
            ]);
        }

        #TODO: implementar a notificação para o cliente
        //$this->announceTicket($ticket);
        $this->informeClient($ticket);
        // (opcional) notifique o cliente sobre a reversão
        // $ticket->client?->notify(new TicketReverted($ticket));
    }

    /**
     * Marca o ticket atual como em atendimento.
     */
    public function inServiceTicket(): void
    {
        if (!$this->currentTicket) {
            return; // nada para atender
        }

        $this->executeWithLock(function () {
            $this->currentTicket->update([
                'status' => QueueTicketStatus::IN_SERVICE->value,
            ]);

            $this->refreshCurrentTicket();
        });

        //$this->announceTicket($this->currentTicket);
        $this->informeClient($this->currentTicket);
    }
}
