<?php

namespace App\Livewire;

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
        return view('livewire.phone-number-register');
    }
}
