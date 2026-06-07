<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Rate limit login attempts - 5 attempts per 15 minutes per IP
        if ($request->path() === 'auth/login' && $request->isMethod('post')) {
            $key = 'login-attempts:' . $request->ip();
            
            if (RateLimiter::tooManyAttempts($key, 5)) {
                return response()->json([
                    'message' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam beberapa menit.'
                ], 429);
            }

            RateLimiter::hit($key, 15 * 60);
        }

        // Rate limit registration - 3 per day per IP
        if ($request->path() === 'auth/register' && $request->isMethod('post')) {
            $key = 'register-attempts:' . $request->ip();
            
            if (RateLimiter::tooManyAttempts($key, 3)) {
                return response()->json([
                    'message' => 'Terlalu banyak pendaftaran dari IP ini. Silakan coba lagi besok.'
                ], 429);
            }

            RateLimiter::hit($key, 24 * 60 * 60);
        }

        return $next($request);
    }
}
