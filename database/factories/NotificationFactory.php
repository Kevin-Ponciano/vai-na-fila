<?php

namespace Database\Factories;

use App\Enums\NotificationMethod;
use App\Enums\NotificationStatus;
use App\Enums\QueueTicketStatus;
use App\Models\Client;
use App\Models\Notification;
use App\Models\QueueTicket;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory()->create()->id,
            'queue_ticket_id' => QueueTicket::factory()->create()->id,
            'method' => $this->faker->randomElement(NotificationMethod::values()),
            'status' => $this->faker->randomElement(NotificationStatus::values()),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
