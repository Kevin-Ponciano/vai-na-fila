<?php

namespace App\Livewire\Client;

use Livewire\Attributes\On;
use Livewire\Component;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReadQr extends Component
{
    public function validateQr(string $qrCode): void
    {
        $validation = false;
        $message = 'QR Code inválido';

        try {
            $fakeRequest = \Request::create($qrCode);
            \Route::getRoutes()->match($fakeRequest);

            $validation = true;
            $message = 'QR Code válido';
        }catch (NotFoundHttpException){
        }

        $this->dispatch('qr-validation', ...[
            'validation' => $validation,
            'message' => $message,
            'qr_code' => $qrCode,
        ]);

    }

    public function render()
    {
        return view('livewire.read-qr')->layout('layouts.client');
    }
}
