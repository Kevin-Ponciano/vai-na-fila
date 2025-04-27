<?php

namespace App\Livewire\Client;

use App\Enums\QueueTicketStatus;
use Auth;
use Livewire\Component;

class MyQueues extends Component
{
    public $tickets = [];

    public function mount()
    {
        $this->tickets = Auth::user()->queueTickets()->where('status', QueueTicketStatus::WAITING->value)
            ->with('queue')->get();
    }

    public function render()
    {
        return view('livewire.my-queues')->layout('layouts.client');
    }
}
