<?php

namespace App\Jobs;

use App\Enums\QueueTicketStatus;
use App\Events\TicketExpiredEvent;
use App\Models\QueueTicket;
use Cache;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExpireTicketJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $ticketId,
        public string $validateCode,
        public string $sessionId,
    )
    {
    }

    public function handle(): void
    {
        $meta = Cache::get($this->cacheKey());
        $ticket = QueueTicket::whereKey($this->ticketId)
            ->where('status', QueueTicketStatus::CALLING)
            ->first();

        if (!$ticket || $meta['validate_code'] != $this->validateCode) {
            return;
        }

        TicketExpiredEvent::dispatch(
            $ticket,
            $this->sessionId,
        );
    }

    private function cacheKey(): string
    {
        return "ticket:{$this->ticketId}:meta";
    }
}
