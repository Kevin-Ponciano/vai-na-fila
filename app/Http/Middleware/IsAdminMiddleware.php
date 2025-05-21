<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class IsAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->isAdmin()) {
            return $next($request);
        } else {
            abort('403', 'Acesso negado');
        }
    }
}
