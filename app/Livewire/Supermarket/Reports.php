<?php

namespace App\Livewire\Supermarket;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Report;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Reports extends Component
{
    public $months = [];

    public function mount(): void
    {
        $this->months = DB::table('reports')
            ->where('supermarket_id', Auth::user()->supermarket_id)
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
            ->pluck(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
            ->map(function ($yearMonth) {
                $date = \Carbon\Carbon::createFromFormat('Y-m', $yearMonth);
                return [
                    'year_month' => $yearMonth,
                    'name' => $date->translatedFormat('F Y'),
                ];
            })
            ->toArray();
    }

    public function downloadReport($yearMonth)
    {
        $supermarketId = Auth::user()->supermarket_id;

        $dados = Report::where('supermarket_id', $supermarketId)
            ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$yearMonth])
            ->with(['supermarket', 'queue'])
            ->get()
            ->map(function ($report) {
                return [
                    $report->id,
                    $report->supermarket->name ?? 'N/A',
                    $report->queue->name ?? 'N/A',
                    $report->avg_wait_time,
                    $report->peak_hour,
                    $report->max_priority_tickets,
                    $report->min_priority_tickets,
                    number_format($report->avg_priority_tickets, 2),
                    $report->max_general_tickets,
                    $report->min_general_tickets,
                    number_format($report->avg_general_tickets, 2),
                    $report->total_tickets,
                    $report->created_at->format('Y-m-d H:i:s'),
                ];
            })
            ->toArray();

        $nomeArquivo = 'relatorio_' . str_replace('-', '_', $yearMonth) . '.xlsx';

        $exportador = new class($dados) implements FromArray, WithHeadings {
            protected $dados;

            public function __construct(array $dados)
            {
                $this->dados = $dados;
            }

            public function array(): array
            {
                return $this->dados;
            }

            public function headings(): array
            {
                return [
                    'ID',
                    'Supermercado',
                    'Fila',
                    'Tempo Médio de Espera (segundos)',
                    'Horário de Pico',
                    'Máximo de Tickets Prioritários',
                    'Mínimo de Tickets Prioritários',
                    'Média de Tickets Prioritários',
                    'Máximo de Tickets Gerais',
                    'Mínimo de Tickets Gerais',
                    'Média de Tickets Gerais',
                    'Total de Tickets',
                    'Criado em',
                ];
            }
        };

        return Excel::download($exportador, $nomeArquivo);
    }

    public function render()
    {
        return view('livewire.reports');
    }
}
