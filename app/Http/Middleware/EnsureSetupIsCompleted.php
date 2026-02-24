<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSetupIsCompleted
{
    /**
     * Handle an incoming request.
     * Redirect to setup wizard if setup hasn't been completed.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If setup is not completed, redirect to setup wizard
        if (! is_setup_completed()) {
            return redirect()->route('setup.index');
        }

        return $next($request);
    }
}
