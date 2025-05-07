<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (!Schema::hasColumn('reports', 'queue_id')) {
                $table->unsignedBigInteger('queue_id')->nullable()->after('supermarket_id');
            } else {
                // Tornar queue_id nullable, se ainda não for
                DB::statement('ALTER TABLE reports MODIFY queue_id BIGINT UNSIGNED NULL');
            }

            if (!Schema::hasColumn('reports', 'max_priority_tickets')) {
                $table->integer('max_priority_tickets')->after('peak_hour');
            }
            if (!Schema::hasColumn('reports', 'min_priority_tickets')) {
                $table->integer('min_priority_tickets')->after('max_priority_tickets');
            }
            if (!Schema::hasColumn('reports', 'avg_priority_tickets')) {
                $table->float('avg_priority_tickets')->after('min_priority_tickets');
            }
            if (!Schema::hasColumn('reports', 'max_general_tickets')) {
                $table->integer('max_general_tickets')->after('avg_priority_tickets');
            }
            if (!Schema::hasColumn('reports', 'min_general_tickets')) {
                $table->integer('min_general_tickets')->after('max_general_tickets');
            }
            if (!Schema::hasColumn('reports', 'avg_general_tickets')) {
                $table->float('avg_general_tickets')->after('min_general_tickets');
            }
            if (!Schema::hasColumn('reports', 'total_tickets')) {
                $table->integer('total_tickets')->after('avg_general_tickets');
            }
        });

        if (Schema::hasColumn('reports', 'queue_id')) {
            // Definir queue_id como NULL para registros onde queue_id não existe em queues
            DB::table('reports')
                ->whereNotNull('queue_id')
                ->whereNotIn('queue_id', DB::table('queues')->pluck('id'))
                ->update(['queue_id' => null]);
        }

        Schema::table('reports', function (Blueprint $table) {
            // Verificar se a chave estrangeira já existe
            $foreignKeys = DB::select("SELECT CONSTRAINT_NAME 
                                       FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                                       WHERE TABLE_NAME = 'reports' 
                                       AND COLUMN_NAME = 'queue_id' 
                                       AND CONSTRAINT_NAME LIKE 'reports_queue_id_foreign'");
            
            if (empty($foreignKeys)) {
                $table->foreign('queue_id')->references('id')->on('queues')->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Remover a chave estrangeira, se existir
            $foreignKeys = DB::select("SELECT CONSTRAINT_NAME 
                                       FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                                       WHERE TABLE_NAME = 'reports' 
                                       AND COLUMN_NAME = 'queue_id' 
                                       AND CONSTRAINT_NAME LIKE 'reports_queue_id_foreign'");
            if (!empty($foreignKeys)) {
                $table->dropForeign(['queue_id']);
            }

            // Remover colunas, se existirem
            $columns = [
                'queue_id',
                'max_priority_tickets',
                'min_priority_tickets',
                'avg_priority_tickets',
                'max_general_tickets',
                'min_general_tickets',
                'avg_general_tickets',
                'total_tickets',
            ];
            $existingColumns = array_filter($columns, fn($column) => Schema::hasColumn('reports', $column));
            if (!empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }
        });
    }
};
