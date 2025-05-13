<?php

namespace App\Events;

use App\Models\QueueTicket;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueueTicketCalledEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public QueueTicket $ticket)
    {
        $this->ticket->load('queue');
    }

    /** Canal público onde o telão vai “escutar” */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("queue.{$this->ticket->queue_id}"),
        ];
    }

    /** Payload extra — aqui você pode adicionar campos adicionais */
    public function broadcastWith(): array
    {
        return [
            'ticket' => $this->ticket,
        ];
    }

    /** (opcional) renomeia o evento no cliente JS */
    public function broadcastAs(): string
    {
        return 'ticket.called';
    }
}
