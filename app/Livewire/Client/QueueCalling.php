<?php

namespace App\Livewire\Client;

use App\Enums\QueueTicketStatus;
use App\Models\QueueTicket;
use Auth;
use Livewire\Component;

class QueueCalling extends Component
{
    public QueueTicket $ticket;

    public function mount($id)
    {
        $this->ticket = Auth::user()->queueTickets()->findOrFail($id);
//        if ($this->ticket->status !== QueueTicketStatus::WAITING->value) {
//            return redirect()
//                ->with('flash.banner', 'Senha ainda em espera ou já chamada!')
//                ->with('flash.bannerStyle', 'danger')
//                ->route('my-queues');
//        }
    }

    public function render()
    {
        return view('livewire.client.queue-calling')->layout('layouts.guest');
    }
}
