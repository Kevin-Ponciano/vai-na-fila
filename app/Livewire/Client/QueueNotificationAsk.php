<?php

namespace App\Livewire\Client;

use Auth;
use Livewire\Component;

class QueueNotificationAsk extends Component
{
    public $queueTicketId;
    public $beNotified = false;

    public function mount()
    {
        $user = Auth::user();
        $this->queueTicketId = request('queue_ticket_id',0);
        $beNotified = (bool)$user->phone;
        if ($beNotified)
           return redirect()->route('queue.position', [
                'id' => $this->queueTicketId,
            ]);
    }

    public function render()
    {
        return view('livewire.queue-notification-ask')->layout('layouts.guest');
    }
}
