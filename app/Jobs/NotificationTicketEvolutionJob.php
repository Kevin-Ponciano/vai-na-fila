<?php

namespace App\Jobs;

use App\Enums\QueueTicketStatus;
use App\Models\QueueTicket;
use App\Services\EvolutionApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotificationTicketEvolutionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public QueueTicket $ticket;

    public function __construct(
        public string $ticketId,
    )
    {
        $this->ticket = QueueTicket::find($this->ticketId);
    }

    public function handle(): void
    {
        $actualStatus = $this->ticket->status;
        switch ($actualStatus) {
            case QueueTicketStatus::CALLING->value:
                app(EvolutionApiService::class)
                    ->ticketStatusCalling($this->ticket->client->phone, $this->ticket);
                break;
            case QueueTicketStatus::EXPIRED->value:
                app(EvolutionApiService::class)
                    ->ticketStatusExpired($this->ticket->client->phone, $this->ticket);
                break;
            case QueueTicketStatus::WAITING->value:
                app(EvolutionApiService::class)
                    ->ticketStatusWaiting($this->ticket->client->phone, $this->ticket);
                break;
        }
    }
}
