<?php

use App\Enums\QueueTicketPriority;
use App\Enums\QueueTicketStatus;
use App\Enums\QueueTicketType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('queue_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('queue_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ticket_number');
            $table->enum('priority', QueueTicketPriority::values())->default(QueueTicketPriority::NORMAL);
            $table->enum('status', QueueTicketStatus::values())->default(QueueTicketStatus::WAITING);
            $table->enum('type', QueueTicketType::values())->default(QueueTicketType::QRCODE);
            $table->timestamp('called_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queue_tickets');
    }
};
