<?php

namespace App\Jobs;

use App\Enums\QueueTicketStatus;
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
        public int $queueTicketId,
        public int $validateCode,
    )
    {
    }

    public function handle(): void
    {
        $ticket = QueueTicket::whereKey($this->queueTicketId)
            ->where('status', QueueTicketStatus::CALLING)
            ->first();

        if (!$ticket) {
            return;
        }

        if ($this->validateCode !== Cache::get($this->cacheKey())) {
            return;
        }

        
    }

    private function cacheKey(): string
    {
        return "ticket:{$this->queueTicketId}:validate_code";
    }
}
