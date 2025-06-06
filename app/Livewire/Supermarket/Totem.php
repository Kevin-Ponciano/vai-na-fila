<?php

namespace App\Livewire\Supermarket;

use App\Enums\QueueTicketPriority;
use App\Enums\QueueTicketStatus;
use App\Enums\QueueTicketType;
use App\Jobs\CancelQrCodeTicketJob;
use App\Models\Queue;
use App\Models\QueueTicket;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Throwable;

class Totem extends Component
{
    /* -----------------------------------------------------------------
     |  Constantes de configuração
     |------------------------------------------------------------------*/
    private const TOKEN_LENGTH = 12;   // caracteres do token
    private const LOCK_TTL_SEC = 3;    // quanto tempo o lock fica ativo
    private const LOCK_WAIT_SEC = 10;    // quanto tempo esperamos pelo lock

    /* -----------------------------------------------------------------
     |  Propriedades públicas (bindadas à view)
     |------------------------------------------------------------------*/
    public Queue $queue;
    public ?string $prioritySelected = null;
    public ?string $prioritySelectedLabel = null;
    public ?string $queueTicketId = null;
    public ?string $token = null;
    public bool $loading = false;
    public ?string $url;
    public int $qrcode_expiration_time;

    /* -----------------------------------------------------------------
     |  Ciclo de vida
     |------------------------------------------------------------------*/
    public function mount(int $id): void
    {
        // garanta 404 se fila não pertencer ao usuário
        $this->queue = auth()->user()?->supermarket->queues->findOrFail($id);

        $this->qrcode_expiration_time = config('vainafila.qrcode_expiration_time');
    }

    /* -----------------------------------------------------------------
     |  Ações de interface
     |------------------------------------------------------------------*/
    /**
     * Seleciona a fila (normal | priority) e gera o QR-code.
     *
     * @throws Throwable
     */
    public function selectedQueue(string $priority): void
    {
        $priorityEnum = QueueTicketPriority::tryFrom($priority)->value
            ?? throw new ModelNotFoundException('Prioridade inválida');
        $this->prioritySelectedLabel = QueueTicketPriority::tryFrom($priority)->name();

        /* --------- 1. Lock para evitar corrida entre múltiplos totens ------------- */
        $lockKey = "queue:{$this->queue->id}:issue_ticket_lock";

        try {
            Cache::lock($lockKey, self::LOCK_TTL_SEC)
                ->block(self::LOCK_WAIT_SEC, function () use ($priorityEnum) {
                    DB::transaction(function () use ($priorityEnum) {

                        /* 2. Cria o ticket */
                        $ticket = $this->queue
                            ->queueTickets()
                            ->create([
                                'priority' => $priorityEnum,
                                'status' => QueueTicketStatus::PROCESSING->value,
                            ]);

                        $this->queueTicketId = (string)$ticket->id;
                        $this->prioritySelected = $priorityEnum;

                        /* 3. Gera token curto porém único (base62 → 12 chars) */
                        $this->token = Str::random(self::TOKEN_LENGTH);

                        Cache::set(
                            $this->token,
                            $this->queueTicketId,
                            now()->addMinutes($this->qrcode_expiration_time + 3)
                        );

                        CancelQrCodeTicketJob::dispatch(
                            $this->queueTicketId,
                            $this->token
                        )->delay(now()->addMinutes($this->qrcode_expiration_time));

                        /* 4. Dispara evento para JS gerar o QR-code */
                        $url = route('queue.join', ['token' => $this->token]);
                        $this->dispatch('generateQrCode', url: $url,wait: $this->qrcode_expiration_time);
                        $this->url = $url;
                    });
                });
        } catch (LockTimeoutException) {
            // opcional: feedback ao usuário
            $this->addError('lock', 'Sistema ocupado. Tente novamente em instantes.');
        }
    }

    /**
     * Cancela a seleção (antes da impressão).
     */
    public function resetQueue(): void
    {
        $this->revertProcessingTicket();       // helper centralizado
        $this->clearState();
    }

    private function revertProcessingTicket(): void
    {
        if (!$this->queueTicketId) {
            return;
        }

        QueueTicket::whereKey($this->queueTicketId)
            ->where('status', QueueTicketStatus::PROCESSING)
            ->first()?->delete();

        Cache::forget($this->token);
    }

    /* -----------------------------------------------------------------
     |  Helpers privados
     |------------------------------------------------------------------*/

    public function clearState(): void
    {
        $this->prioritySelected = null;
        $this->queueTicketId = null;
        $this->token = null;
        $this->loading = false;
    }

    /**
     * Confirma a impressão e devolve ticket ao fluxo normal.
     */
    public function printQrCode(): void
    {
        if ($this->queueTicketId) {
            QueueTicket::whereKey($this->queueTicketId)
                ->where('status', QueueTicketStatus::PROCESSING)
                ->update([
                    'type' => QueueTicketType::PRINT,
                    'status' => QueueTicketStatus::WAITING,
                ]);
        }

        Cache::forget($this->token);
        $this->clearState();
    }

    /* -----------------------------------------------------------------
     |  Renderização
     |------------------------------------------------------------------*/

    public function render()
    {
        return view('livewire.totem')->layout('layouts.guest');
    }
}
