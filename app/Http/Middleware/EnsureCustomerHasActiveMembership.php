<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerHasActiveMembership
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()?->getName();

        if ($routeName && str_starts_with($routeName, 'portal.attendance')) {
            return $next($request);
        }

        if (business_type() !== 'gym') {
            return $next($request);
        }

        $customer = $request->user('customer');

        if (! $customer) {
            return $next($request);
        }

        if ($customer->hasActiveMembership()) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => __('portal.membership_required'),
            ], 403);
        }

        return redirect()
            ->route('portal.dashboard')
            ->with('error', __('portal.membership_required'));
    }
}
