<?php

namespace Database\Factories;

use App\Models\Supermarket;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SupermarketFactory extends Factory
{
    protected $model = Supermarket::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'cnpj' => $this->faker->cnpj(false),
            'phone' => $this->faker->cellphoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'state' => $this->faker->regionAbbr(),
            'zip_code' => $this->faker->postcode(),
            'opening_hours' => $this->faker->time(),
            'closing_hours' => $this->faker->time(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
