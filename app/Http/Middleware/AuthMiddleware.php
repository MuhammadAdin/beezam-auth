<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek session login
        if (!session()->has('user_id')) {
            return redirect()->route('login')
                             ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Lanjutkan request
        $response = $next($request);

        // ===== SECURITY HEADERS =====
        // Mencegah clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Mencegah MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Aktifkan XSS protection browser
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        return $response;
    }
}