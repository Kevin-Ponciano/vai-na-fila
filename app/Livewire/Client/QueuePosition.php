<?php

namespace App\Livewire\Client;

use App\Models\Queue;
use Livewire\Component;

class QueuePosition extends Component
{
    public Queue $queue;

    public function mount($id)
    {
        $this->queue = Queue::findOrFail($id);
    }
    public function render()
    {
        return view('livewire.queue-position')->layout('layouts.client');
    }
}
