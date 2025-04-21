<?php

use App\Livewire\MyQueues;
use App\Livewire\PhoneNumberRegister;
use App\Livewire\QueueManager;
use App\Livewire\QueueNotificationAsk;
use App\Livewire\QueuePosition;
use App\Livewire\Queues;
use App\Livewire\QueueScreen;
use App\Livewire\ReadQr;
use App\Livewire\Reports;
use App\Livewire\Totem;
use App\Livewire\Users;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\RoutePath;

Route::redirect('/', '/supermarket/filas')->name('home');

Route::prefix('supermarket')->middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/filas', Queues::class)->name('queues');
    Route::get('/filas/{id}', QueueManager::class)->name('queues.show');
    Route::get('/filas/{id}/visor', QueueScreen::class)->name('queues.screen');
    Route::get('/filas/{id}/totem', Totem::class)->name('queues.totem');


    Route::get('/relatorios', Reports::class)->name('reports');
    Route::get('/usuarios', Users::class)->name('users');

    Route::get(RoutePath::for('logout', '/logout'), [AuthenticatedSessionController::class, 'destroy'])
        ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
        ->name('logout');
});

Route::prefix('cliente')->group(function () {
    Route::get('/receber-notificacao', QueueNotificationAsk::class)->name('queue.notification.ask');
    Route::get('/registrar-telefone', PhoneNumberRegister::class)->name('phone-number.register');
    Route::get('/fila/{id}', QueuePosition::class)->name('queue.position');
    Route::get('/minhas-filas', MyQueues::class)->name('my-queues');
    Route::get('/ler-qrcode', ReadQr::class)->name('read-qr');
});
