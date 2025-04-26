<?php

namespace App\Http\Controllers;

use App\Models\Client;

class ClientAuthController extends Controller
{
    public function authenticate()
    {
        $client = Client::find(session('client_id'));
        if (!$client) {
            $client = Client::create([]);
            session(['client_id' => $client->id]);
        }

        \Auth::guard('client_users')->login($client);

        return redirect()->route('my-queues');
    }
}
