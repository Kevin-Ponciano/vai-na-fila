<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->foreignId('queue_id')->constrained()->cascadeOnDelete()->after('supermarket_id');
            $table->integer('max_priority_tickets')->after('peak_hour');
            $table->integer('min_priority_tickets')->after('max_priority_tickets');
            $table->float('avg_priority_tickets')->after('min_priority_tickets');
            $table->integer('max_general_tickets')->after('avg_priority_tickets');
            $table->integer('min_general_tickets')->after('max_general_tickets');
            $table->float('avg_general_tickets')->after('min_general_tickets');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['queue_id']);
            $table->dropColumn([
                'queue_id',
                'max_priority_tickets',
                'min_priority_tickets',
                'avg_priority_tickets',
                'max_general_tickets',
                'min_general_tickets',
                'avg_general_tickets',
            ]);
        });
    }
};