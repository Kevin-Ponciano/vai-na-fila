<?php

namespace App\Livewire;

use App\Models\Queue;
use App\Models\QueueTicket;
use Livewire\Component;

class QueueScreen extends Component
{

    public ?QueueTicket $ticket;
    public Queue $queue;

    public function mount($id)
    {
        $this->queue = \Auth::user()->supermarket->queues->find($id);
        $this->ticket = $this->queue->currentTicket();
    }
    public function render()
    {
        return view('livewire.queue-screen')->layout('layouts.guest');
    }
}
