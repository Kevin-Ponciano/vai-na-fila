<?php

use App\Http\Controllers\ClientAuthController;
use App\Http\Middleware\ClientAuthMiddleware;
use App\Livewire\Client\MyQueues;
use App\Livewire\Client\PhoneNumberRegister;
use App\Livewire\Client\QueueCalling;
use App\Livewire\Client\QueueNotificationAsk;
use App\Livewire\Client\QueuePosition;
use App\Livewire\Client\ReadQr;
use App\Livewire\Supermarket\QueueManager;
use App\Livewire\Supermarket\Queues;
use App\Livewire\Supermarket\QueueScreen;
use App\Livewire\Supermarket\Reports;
use App\Livewire\Supermarket\Totem;
use App\Livewire\Supermarket\Users;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\RoutePath;

Route::redirect('/', '/supermarket/filas')->name('home');

Route::prefix('supermarket')->middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('filas', Queues::class)->name('queues');
    Route::get('filas/{id}', QueueManager::class)->name('queues.show');
    Route::get('filas/{id}/visor', QueueScreen::class)->name('queues.screen');
    Route::get('filas/{id}/totem', Totem::class)->name('queues.totem');


    Route::get('relatorios', Reports::class)->name('reports');
    Route::get('usuarios', Users::class)->name('users');

    Route::get(RoutePath::for('logout', '/logout'), [AuthenticatedSessionController::class, 'destroy'])
        ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
        ->name('logout');
});

Route::prefix('cliente')->middleware(ClientAuthMiddleware::class)->group(function () {
    Route::get('receber-notificacao', QueueNotificationAsk::class)->name('queue.notification.ask');
    Route::get('registrar-telefone', PhoneNumberRegister::class)->name('phone-number.register');
    Route::get('fila/{id}', QueuePosition::class)->name('queue.position');
    Route::get('minhas-filas', MyQueues::class)->name('my-queues');
    Route::get('ler-qrcode', ReadQr::class)->name('read-qr');
    Route::get('entrar-fila/{token}', [ClientAuthController::class, 'joinQueue'])
        ->withoutMiddleware(ClientAuthMiddleware::class)
        ->name('queue.join');
    Route::get('sua-vez/{id}', QueueCalling::class)->name('queue.calling');
});

Route::get('cliente/login', [ClientAuthController::class, 'authenticate'])->name('client.login');
