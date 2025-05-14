<?php

namespace App\Livewire\Client;

use App\Enums\QueueTicketStatus;
use App\Models\Queue;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class QueuePosition extends Component
{
    public $ticket;
    public Queue $queue;

    public function mount(int $id)
    {
        $this->ticket = Auth::user()
            ->queueTickets()
            ->whereKey($id)
            ->where(function (Builder $query) {
                $query->whereIn('status', [
                    QueueTicketStatus::WAITING,
                    QueueTicketStatus::CALLING,
                    QueueTicketStatus::IN_SERVICE,
                ])->orExpiredStillValid();   // ← escopo
            })
            ->first();

        if (!$this->ticket) {
            return redirect()->route('my-queues');
        }

        if ($this->ticket->status === QueueTicketStatus::CALLING->value) {
            return redirect()->route('queue.calling', ['id' => $this->ticket->id]);
        }

        $this->queue = $this->ticket->queue;
    }

    public function leaveQueue()
    {
        $this->ticket->update([
            'status' => QueueTicketStatus::CALLED->value,
        ]);

        return redirect()->route('my-queues')
            ->with('flash.banner', 'Você saiu da fila com sucesso!')
            ->with('flash.bannerStyle', 'success');
    }

    public function render()
    {
        return view('livewire.queue-position')->layout('layouts.client');
    }
}
