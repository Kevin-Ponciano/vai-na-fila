<?php

namespace App\Livewire\Supermarket;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Reports extends Component
{
    public $reports = [];
    public function mount(): void
    {
        $this->reports = Auth::user()->supermarket->reports;
    }
    public function render()
    {
        return view('livewire.reports');
    }
}
