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

    public function __construct(public QueueTicket $queueTicket)
    {
        $this->queueTicket->load('queue');
        #TODO: Continuar daqui :https://chatgpt.com/c/68228142-3734-800f-b999-e71dc6df00b9
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name')
        ];
    }
}
