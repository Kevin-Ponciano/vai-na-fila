<?php

namespace App\Livewire;

use App\Models\Queue;
use Livewire\Component;

class QueueRealTimeScreen extends Component
{

    public Queue $queue;

    public function mount($id)
    {
        $this->queue = \Auth::user()->supermarket->queues->find($id);
    }
    public function render()
    {
        return view('livewire.queue-real-time-screen');
    }
}
