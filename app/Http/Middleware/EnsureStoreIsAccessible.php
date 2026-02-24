<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStoreIsAccessible
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        if ($user->isAdmin()) {
            if (! $request->isMethodSafe() && ! current_store() && ! $this->isExemptRoute($request)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => __('store.select_store_first'),
                    ], 403);
                }

                return redirect()
                    ->route('stores.index')
                    ->with('error', __('store.select_store_first'));
            }

            return $next($request);
        }

        $store = current_store();

        if (! $store) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __('store.select_store_first'),
                ], 403);
            }

            return redirect()
                ->route('stores.index')
                ->with('error', __('store.select_store_first'));
        }

        if ($store->is_active && $store->is_approved) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Store belum aktif atau belum disetujui.',
            ], 403);
        }

        return redirect()
            ->route('dashboard')
            ->with('error', 'Store belum aktif atau belum disetujui.');
    }

    protected function isExemptRoute(Request $request): bool
    {
        $routeName = $request->route()?->getName();

        if (! $routeName) {
            return false;
        }

        return str_starts_with($routeName, 'stores.')
            || str_starts_with($routeName, 'settings.')
            || str_starts_with($routeName, 'staff.')
            || str_starts_with($routeName, 'imports.')
            || $routeName === 'logout';
    }
}
