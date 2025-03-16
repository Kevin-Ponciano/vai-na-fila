<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\Supermarket;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'supermarket_id' => Supermarket::factory()->create()->id,
            'avg_wait_time' => $this->faker->numberBetween(1, 60), // Average wait time in minutes
            'peak_hour' => $this->faker->dateTimeBetween('-1 month', 'now'), // Peak hour within the last month
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
