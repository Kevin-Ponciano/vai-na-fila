<?php

namespace App\Http\Controllers;

use App\Enums\QueueTicketStatus;
use App\Models\Client;
use App\Models\QueueTicket;
use Auth;
use Cache;

class ClientAuthController extends Controller
{
    public function joinQueue($token)
    {
        $ticketId = Cache::get($token);
        if(!$ticketId) {
            return redirect()->route('my-queues');
        }
        $ticket = QueueTicket::whereKey($ticketId)
            ->where('status', QueueTicketStatus::PROCESSING->value)
            ->firstOrFail();

        $user = $this->authenticate();

        $ticket->update([
            'client_id' => $user->id,
            'status' => QueueTicketStatus::WAITING->value,
        ]);

        Cache::forget($token);

        return redirect()->route('queue.notification.ask', ['queue_ticket_id' => $ticket->id]);
    }

    public function authenticate()
    {
        $client = Client::find(session('client_id'));
        if (!$client) {
            $client = Client::create([]);
            session(['client_id' => $client->id]);
        }

        Auth::guard('client_users')->login($client);

        return $client;
    }
}
