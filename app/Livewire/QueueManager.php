<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Queue;

class QueueManager extends Component
{
    public Queue $queue;
    public function mount($id)
    {

        $this->queue = \Auth::user()->supermarket->queues->find($id);
    }
    public function render()
    {
        return view('livewire.queue-manager');
    }
}
