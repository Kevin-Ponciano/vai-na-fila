<?php

namespace App\Livewire\Client;

use App\Models\Queue;
use Livewire\Component;

class MyQueues extends Component
{
    public $queues = [];

    public function mount()
    {
        $this->queues = Queue::limit(3)->get();

        debug(\Auth::user());
        debug(session()->all());
    }

    public function render()
    {
        return view('livewire.my-queues')->layout('layouts.client');
    }
}
