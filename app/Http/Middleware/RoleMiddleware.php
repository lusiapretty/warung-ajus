<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param string $role
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Memeriksa apakah pengguna terautentikasi dan memiliki peran yang benar
        if (!Auth::check() || Auth::user()->role != $role) {
            abort(403, 'Unauthorized');
        }
        return $next($request);
    }
}
