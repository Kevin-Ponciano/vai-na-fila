<?php

namespace App\Livewire;

use Livewire\Component;

class QueueNotificationAsk extends Component
{
    public $queueTicket;
    public $beNotified;
    public function render()
    {
        return view('livewire.queue-notification-ask')->layout('layouts.guest');
    }
}
