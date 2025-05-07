<?php

namespace Database\Seeders;

use App\Enums\QueueTicketStatus;
use App\Enums\UserRole;
use App\Models\AdminUser;
use App\Models\QueueTicket;
use App\Models\Report;
use App\Models\Supermarket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $supermarkets = Supermarket::factory(2)->create();

        AdminUser::create([
            'name' => 'admin',
            'email' => 'admin@admin',
            'password' => bcrypt('123'),
        ]);

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

            $queues = $supermarket->queues;
            $timestamp = Carbon::now();
            foreach ($queues as $queue) {
                for ($i = 0; $i < 10; $i++) {
                    $timestamp->addMinutes(2);
                    QueueTicket::factory()->create([
                        'queue_id' => $queue->id,
                        'status' => QueueTicketStatus::WAITING,
                        'created_at' => $timestamp
                    ]);
                }
            }
            //$this->createReports($supermarket->id);
        }
    }

    private function createReports($supermarketId): void
    {
        $startDate = Carbon::now()->startOfYear();
        $endDate = Carbon::now();

        while ($startDate < $endDate) {
            $currentYear = $startDate->year;
            $month = $startDate->month;
            $createdAt = Carbon::create($currentYear, $month, 1);

            Report::factory()->create([
                'supermarket_id' => $supermarketId,
                'created_at' => $createdAt
            ]);

            $startDate->addMonth();
        }
    }
}
