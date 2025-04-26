<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class ClientAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if(Auth::guard('client_users')->check()) {
            Auth::shouldUse('client_users');
            return $next($request);
        }else{
            abort('403', 'É necessário scannear o QR Code para acessar essa página.');
        }
    }
}
