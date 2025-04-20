<?php

namespace App\Livewire;

use Livewire\Component;

class Totem extends Component
{
    public $queue;
    public $ticket;

    public function mount($id)
    {
        $this->queue = \Auth::user()->supermarket->queues->find($id);
        $this->ticket = $this->queue->currentTicket();
    }
    public function render()
    {
        return view('livewire.totem')->layout('layouts.guest');
    }
}
