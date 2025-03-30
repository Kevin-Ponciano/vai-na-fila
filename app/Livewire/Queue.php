<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Queue extends Component
{
    public $queues = [];
    public $header = 'Filas Disponiveis';

    public function mount()
    {
        $this->queues = Auth::user()->supermarket->queues;
    }

    public function render()
    {
        return view('livewire.queue',['header' => $this->header]);
    }
}
