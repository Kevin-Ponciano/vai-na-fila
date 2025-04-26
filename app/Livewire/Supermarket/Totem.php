<?php

namespace App\Livewire\Supermarket;

use App\Enums\QueueTicketStatus;
use App\Models\QueueTicket;
use Auth;
use Cache;
use Livewire\Component;
use Str;

class Totem extends Component
{
    public $queue;
    public $prioritySelected = null;
    public $queueTicketId;
    public $token;

    public function mount($id)
    {
        $this->queue = Auth::user()->supermarket->queues->find($id);
    }

    public function selectedQueue($priority): void
    {
        $this->prioritySelected = $priority;
        $this->queueTicketId = $this->queue->queueTickets()->create([
            'priority' => $this->prioritySelected,
            'status' => QueueTicketStatus::PROCESSING->value
        ])->id;

        $this->token = Str::random('12');
        $expInMinutes = 10;

        Cache::set($this->token, $this->queueTicketId, 60 * $expInMinutes);
    }

    public function resetQueue(): void
    {
        $this->prioritySelected = null;
        $ticket = QueueTicket::where('status', QueueTicketStatus::PROCESSING->value)
            ->find($this->queueTicketId);
        if ($ticket) {
            $ticket->update([
                'status' => QueueTicketStatus::CANCELLED->value
            ]);
            Cache::forget($this->token);

            $this->queueTicketId = null;
            $this->token = null;
        }
    }


    public function render()
    {
        return view('livewire.totem')->layout('layouts.guest');
    }
}
