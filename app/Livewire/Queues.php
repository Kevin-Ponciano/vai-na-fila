<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Queues extends Component
{
    public $queues = [];

    public function mount(): void
    {
        $this->queues = Auth::user()->supermarket->queues;
    }

    public function render()
    {
        return view('livewire.queues');
    }
}
