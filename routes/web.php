<?php

use App\Livewire\Queue;
use App\Livewire\Reports;
use App\Livewire\Users;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\RoutePath;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/filas', Queue::class)->name('queues');
    Route::get('/relatorios', Reports::class)->name('reports');
    Route::get('/usuarios', Users::class)->name('users');
    
    Route::get(RoutePath::for('logout', '/logout'), [AuthenticatedSessionController::class, 'destroy'])
        ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
        ->name('logout');
});
