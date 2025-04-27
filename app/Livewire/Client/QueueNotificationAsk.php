<?php

namespace App\Livewire\Client;

use Auth;
use Livewire\Component;

class QueueNotificationAsk extends Component
{
    public $queueTicket;
    public $beNotified;

    public function mount()
    {
        $user = Auth::user();
        $queueTicketId = request('queue_ticket_id',0);

        if ($user->phone)
           return redirect()->route('queue.position', [
                'id' => $queueTicketId
            ]);
    }

    public function render()
    {
        return view('livewire.queue-notification-ask')->layout('layouts.guest');
    }
}
