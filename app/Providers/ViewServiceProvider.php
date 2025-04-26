<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $user = auth()->user();
            $view->with([
                
            ]);
        });
    }
}
