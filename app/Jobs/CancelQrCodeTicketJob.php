<?php

namespace App\Jobs;

use App\Enums\QueueTicketStatus;
use App\Models\QueueTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CancelQrCodeTicketJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly string $ticketId,
        private readonly string $token
    )
    {
    }

    public function handle(): void
    {
        $ticket = QueueTicket::whereKey($this->ticketId)
            ->where('status', QueueTicketStatus::PROCESSING->value)
            ->first();


    }
}
