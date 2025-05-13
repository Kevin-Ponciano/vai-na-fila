<?php

namespace App\Events;

use App\Models\QueueTicket;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketExpiredEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public QueueTicket $queueTicket,
        public string      $sessionId,
    )
    {
        $this->queueTicket->load('queue');
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('queue.' . $this->queueTicket->queue_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ticket.expired';
    }

    public function broadcastWith(): array
    {
        return [
            'ticket_id' => $this->queueTicket->id,
            'session_id' => $this->sessionId,
        ];
    }
}
