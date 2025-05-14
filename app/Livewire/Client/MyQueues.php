<?php

namespace App\Livewire\Client;

use App\Enums\QueueTicketStatus;
use Auth;
use Illuminate\Database\Eloquent\Builder;
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
            ->where(function (Builder $query) {
                $query->whereIn('status', [
                    QueueTicketStatus::WAITING,
                    QueueTicketStatus::CALLING,
                    QueueTicketStatus::IN_SERVICE,
                ])->orExpiredStillValid();   // ← escopo
            })
            ->with('queue')
            ->get();
    }

    public function render()
    {
        return view('livewire.my-queues')->layout('layouts.client');
    }
}
