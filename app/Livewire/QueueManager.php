<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Queue;

class QueueManager extends Component
{
    public Queue $queue;
    public function mount(Queue $queue)
    {
        $this->queue = $queue;
    }
    public function render()
    {
        debug($this->queue);
        return view('livewire.queue-manager');
    }
}
