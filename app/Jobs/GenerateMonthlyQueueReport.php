<?php

namespace App\Jobs;

use App\Enums\QueueTicketPriority;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Report;

class GenerateMonthlyQueueReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $startOfLastMonth = now()->subMonth()->startOfMonth();
        $endOfLastMonth = now()->subMonth()->endOfMonth();

        $queues = DB::table('queues')->select('id', 'supermarket_id')->get();

        if ($queues->isEmpty()) {
            Log::info('Nenhuma fila encontrada para gerar relatórios.');
            return;
        }

        foreach ($queues as $queue) {
            Log::info("Processando relatório para fila ID: {$queue->id}, Supermercado ID: {$queue->supermarket_id}");

            $priorityStats = DB::table('queue_tickets')
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as ticket_count')
                )
                ->where('queue_id', $queue->id)
                ->where('priority', QueueTicketPriority::PRIORITY)
                ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
                ->groupBy('date')
                ->get();

            $priorityCounts = $priorityStats->pluck('ticket_count')->toArray();
            $maxPriority = !empty($priorityCounts) ? max($priorityCounts) : 0;
            $minPriority = !empty($priorityCounts) ? min($priorityCounts) : 0;
            $avgPriority = !empty($priorityCounts) ? array_sum($priorityCounts) / count($priorityCounts) : 0;

            $generalStats = DB::table('queue_tickets')
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as ticket_count')
                )
                ->where('queue_id', $queue->id)
                ->where('priority', QueueTicketPriority::NORMAL)
                ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
                ->groupBy('date')
                ->get();

            $generalCounts = $generalStats->pluck('ticket_count')->toArray();
            $maxGeneral = !empty($generalCounts) ? max($generalCounts) : 0;
            $minGeneral = !empty($generalCounts) ? min($generalCounts) : 0;
            $avgGeneral = !empty($generalCounts) ? array_sum($generalCounts) / count($generalCounts) : 0;

            $totalTickets = DB::table('queue_tickets')
                ->where('queue_id', $queue->id)
                ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
                ->count();

            $avgWaitTime = DB::table('queue_tickets')
                ->where('queue_id', $queue->id)
                ->whereNotNull('called_at')
                ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
                ->avg(DB::raw('TIMESTAMPDIFF(SECOND, created_at, called_at)'));

            $peakHour = DB::table('queue_tickets')
                ->select(DB::raw('HOUR(created_at) as hour'))
                ->where('queue_id', $queue->id)
                ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
                ->groupBy('hour')
                ->orderByRaw('COUNT(*) DESC')
                ->limit(1)
                ->value('hour');

            Report::create([
                'supermarket_id' => $queue->supermarket_id,
                'queue_id' => $queue->id,
                'avg_wait_time' => (int) ($avgWaitTime ?? 0),
                'peak_hour' => $peakHour ? sprintf('%02d:00:00', $peakHour) : '00:00:00',
                'max_priority_tickets' => $maxPriority,
                'min_priority_tickets' => $minPriority,
                'avg_priority_tickets' => $avgPriority,
                'max_general_tickets' => $maxGeneral,
                'min_general_tickets' => $minGeneral,
                'avg_general_tickets' => $avgGeneral,
                'total_tickets' => $totalTickets,
            ]);

            Log::info("Relatório gerado para fila ID: {$queue->id}");
        }
    }
}