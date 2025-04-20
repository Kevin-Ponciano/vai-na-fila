<?php

use App\Enums\QueueType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supermarket_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('type', QueueType::values());
            $table->string('image_path')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
