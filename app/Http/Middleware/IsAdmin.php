<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && in_array((int) Auth::user()->is_superadmin, [1, 2, 3])) {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'Akses ditolak. Anda bukan Admin.');
    }
}
