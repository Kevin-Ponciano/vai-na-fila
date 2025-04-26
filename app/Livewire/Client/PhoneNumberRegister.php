<?php

namespace App\Livewire\Client;

use Livewire\Component;

class PhoneNumberRegister extends Component
{
    public string $phoneNumber = '';

    public function save(): void
    {
        redirect()->route('queue.position', ['id' => 1]);
    }
    public function render()
    {
        return view('livewire.phone-number-register')->layout('layouts.client');
    }
}
