<?php

namespace App\Livewire\Client;

use Livewire\Attributes\On;
use Livewire\Component;

class ReadQr extends Component
{
    public $qrCodeData;

    #[On('qrCodeDetected')]
    public function handleQrCodeScanned($qrCode): void
    {
       debug($qrCode);
    }

    public function render()
    {
        return view('livewire.read-qr')->layout('layouts.client');
    }
}
