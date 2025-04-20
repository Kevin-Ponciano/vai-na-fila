<?php

namespace App\Events;

use App\Models\QueueTicket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueueTicketCalledEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $queueId;
    public QueueTicket $ticket;

    public function __construct(QueueTicket $ticket)
    {
        // passe apenas o que o telão precisa renderizar
        $this->queueId = $ticket->queue_id;
        $this->ticket = $ticket;
    }

    /** Canal público onde o telão vai “escutar” */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel("queue.{$this->queueId}");
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
