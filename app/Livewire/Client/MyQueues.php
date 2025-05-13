<?php

namespace App\Livewire\Client;

use App\Enums\QueueTicketStatus;
use Auth;
use Carbon\Carbon;
use Livewire\Component;

// <-- lembre de importar

class MyQueues extends Component
{
    const EXPIRATION_TIME_MAX = 5;   // minutos

    public $tickets = [];

    public function mount(): void
    {
        $this->tickets = Auth::user()
            ->queueTickets()
            ->whereIn('status', [
                QueueTicketStatus::WAITING,
                QueueTicketStatus::CALLING,
                QueueTicketStatus::IN_SERVICE
            ])
            ->orExpiredStillValid()
            ->with('queue')
            ->get();
    }

    public function render()
    {
        return view('livewire.my-queues')->layout('layouts.client');
    }
}
