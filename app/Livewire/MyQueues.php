<?php

namespace App\Livewire;

use App\Models\Queue;
use Livewire\Component;

class MyQueues extends Component
{
    public $queues = [];

    public function mount()
    {
        $this->queues = Queue::limit(3)->get();
    }

    public function render()
    {
        return view('livewire.my-queues');
    }
}
