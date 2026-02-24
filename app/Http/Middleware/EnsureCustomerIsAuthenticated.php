<?php

namespace App\Http\Middleware;

use App\Models\Store;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerIsAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('customer')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->guest(route('portal.login'));
        }

        $customer = Auth::guard('customer')->user();

        if ($customer && $customer->store_id && $request->hasSession()) {
            if (! $request->session()->get('store_id')) {
                $request->session()->put('store_id', $customer->store_id);
            }

            if (! $request->session()->get('business_type_filter')) {
                $store = Store::query()->find($customer->store_id);

                if ($store) {
                    $request->session()->put('business_type_filter', $store->business_type);
                }
            }
        }

        return $next($request);
    }
}
