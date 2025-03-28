<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Queue;
use App\Models\QueueTicket;
use App\Models\Supermarket;
use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $supermarkets = Supermarket::factory(5)->create();

        foreach ($supermarkets as $supermarket) {
            $user = User::factory()->create([
                'name' => 'user' . $supermarket->id,
                'email' => 'user' . $supermarket->id . '@gmail.com',
                'supermarket_id' => $supermarket->id,
                'role' => UserRole::OPERATOR,
            ]);

            $admin = User::factory()->create([
                'name' => 'admin' . $supermarket->id,
                'email' => 'admin' . $supermarket->id . '@gmail.com',
                'supermarket_id' => $supermarket->id,
                'role' => UserRole::ADMIN,
            ]);

            $queue = Queue::create([
                'supermarket_id' => $supermarket->id,
                'name' => fake()->name(),
                'is_priority' => false,
            ]);

            QueueTicket::factory(10)->create([
                'queue_id' => $queue->id,
            ]);

            $queuePriority = Queue::create([
                'supermarket_id' => $supermarket->id,
                'name' => fake()->name(),
                'is_priority' => true,
            ]);

            QueueTicket::factory(10)->create([
                'queue_id' => $queuePriority->id,
            ]);
        }
    }
}
