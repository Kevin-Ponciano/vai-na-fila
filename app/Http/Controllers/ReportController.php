<?php

namespace App\Http\Controllers;

use App\Exports\DailyQueueReport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function generateDailyQueueReport($supermarketId, $year, $month)
    {
        return Excel::download(new DailyQueueReport($supermarketId, $year, $month), 'relatorio_filas_' . $year . '_' . $month . '.xlsx');
    }
}