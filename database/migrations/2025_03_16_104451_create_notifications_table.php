<?php

use App\Enums\NotificationMethod;
use App\Enums\NotificationStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('queue_ticket_id')->constrained()->cascadeOnDelete();
            $table->enum('method', NotificationMethod::values());
            $table->enum('status', NotificationStatus::values())->default(NotificationStatus::SENT);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
