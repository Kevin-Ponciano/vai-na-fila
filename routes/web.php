<?php

use App\Livewire\QueueScreen;
use App\Livewire\Queues;
use App\Livewire\QueueManager;
use App\Livewire\Reports;
use App\Livewire\Users;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\RoutePath;

Route::redirect('/', '/filas');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/filas', Queues::class)->name('queues');
    Route::get('/filas/{id}', QueueManager::class)->name('queues.show');
    Route::get('/filas/{id}/visor', QueueScreen::class)->name('queues.screen');
    Route::get('/filas/{id}/totem', \App\Livewire\Totem::class)->name('queues.totem');


    Route::get('/relatorios', Reports::class)->name('reports');
    Route::get('/usuarios', Users::class)->name('users');

    Route::get(RoutePath::for('logout', '/logout'), [AuthenticatedSessionController::class, 'destroy'])
        ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
        ->name('logout');
});
