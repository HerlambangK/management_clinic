<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSetupIsRequired
{
    /**
     * Handle an incoming request.
     * Only allow access to setup pages if setup hasn't been completed.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If setup is already completed, redirect to dashboard or home
        if (is_setup_completed()) {
            if (auth()->check()) {
                return redirect()->route('dashboard');
            }

            return redirect()->route('home');
        }

        return $next($request);
    }
}
