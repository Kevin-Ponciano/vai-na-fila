<?php

namespace App\Livewire\Client;

use App\Enums\QueueTicketStatus;
use App\Models\QueueTicket;
use Auth;
use Livewire\Component;

class QueueCalling extends Component
{
    public QueueTicket $ticket;
    public $timeLeft;

    public function mount($id)
    {
        $this->ticket = Auth::user()->queueTickets()->findOrFail($id);

        if ($this->ticket->status !== QueueTicketStatus::CALLING->value) {
            $message = $this->ticket->status === QueueTicketStatus::IN_SERVICE->value
                ? 'Você Está em Atendimento!'
                : 'Senha já chamada ou ainda em espera!';
            return redirect()
                ->with('flash.banner', $message)
                ->with('flash.bannerStyle', 'danger')
                ->route('my-queues');
        }

        $this->timeLeft = $this->ticket->called_at
            ->addMinutes((int)config('vainafila.ticket_expiration_time'))
            ->addSeconds(5)
            ->diffInSeconds(now());
        if ($this->timeLeft >= 0) {
            $this->timeLeft = 0;
        }
    }

    public function render()
    {
        return view('livewire.client.queue-calling')->layout('layouts.guest');
    }
}
