<?php

use App\Models\Client;
use App\Models\Queue;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});

Broadcast::channel('queue.{queueId}', function (User $user, $queueId) {
    return $user->supermarket_id === Queue::find($queueId)->supermarket_id;
});

Broadcast::channel('client.{clientId}.call', function (Client $client, $clientId) {
    return (int)$client->id === (int)$clientId;
},[
    'guards' => ['client_users'],
]);

