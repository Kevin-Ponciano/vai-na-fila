<?php

namespace Database\Factories;

use App\Models\QueueOffer;
use App\Models\QueueTicket;
use App\Models\Supermarket;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class QueueOfferFactory extends Factory
{
    protected $model = QueueOffer::class;

    public function definition(): array
    {
        return [
            'supermarket_id' => Supermarket::factory()->create()->id,
            'queue_ticket_id' => QueueTicket::factory()->create()->id,
            'product_name' => $this->faker->word(),
            'description' => $this->faker->text(),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'discount_percentage' => $this->faker->randomFloat(2, 1, 100),
            'start_date' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 months'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
