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
        $this->tickets = Auth::user()->queueTickets()
            ->whereIn('status', [QueueTicketStatus::WAITING, QueueTicketStatus::CALLING, QueueTicketStatus::IN_SERVICE])
            ->with('queue')->get();
    }

    public function render()
    {
        return view('livewire.my-queues')->layout('layouts.client');
    }
}
