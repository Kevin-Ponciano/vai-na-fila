<?php

namespace App\Livewire\Client;

use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PhoneNumberRegister extends Component
{
    public string $phoneNumber = '';
    public int $queueTicketId;
    public Client $user;

    public function mount()
    {
        $this->user = Auth::user();
        $this->queueTicketId = request('queue_ticket_id',0);
        $beNotified = (bool)$this->user->phone;
        if ($beNotified)
           return redirect()->route('queue.position', [
                'id' => $this->queueTicketId,
            ]);
    }

    public function save(): void
    {
        $this->user->update([
            'phone' => $this->phoneNumber,
        ]);
        redirect()->route('queue.position', ['id' => $this->queueTicketId]);
    }
    public function render()
    {
        return view('livewire.phone-number-register')->layout('layouts.guest');
    }
}
