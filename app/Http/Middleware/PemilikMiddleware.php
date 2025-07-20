<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PemilikMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah pengguna sudah login dan memiliki peran 'pemilik'
        if (Auth::check() && Auth::user()->role == 'pemilik') {
            return $next($request);
        }

        // Jika tidak, kembalikan halaman 'unauthorized'
        abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
    }
}