<?php

namespace Database\Factories;

use App\Enums\QueueTicketPriority;
use App\Enums\QueueTicketStatus;
use App\Enums\QueueTicketType;
use App\Models\Client;
use App\Models\QueueTicket;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class QueueTicketFactory extends Factory
{
    protected $model = QueueTicket::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory()->create()->id,
            'priority' => $this->faker->randomElement(QueueTicketPriority::values()),
            'status' => $this->faker->randomElement(QueueTicketStatus::values()),
            'type' => $this->faker->randomElement(QueueTicketType::values()),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

}
