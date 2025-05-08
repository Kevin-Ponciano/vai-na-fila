<?php

use App\Jobs\GenerateMonthlyQueueReport;
use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new GenerateMonthlyQueueReport)
    ->monthlyOn('01','0:0')
    ->onFailure(function () {
        Log::error('Falha ao gerar relatório mensal de filas.');
    });
