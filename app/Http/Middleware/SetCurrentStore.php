<?php

namespace App\Http\Middleware;

use App\Models\Store;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentStore
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $storeParam = $request->input('store');
        $businessTypeFilter = $this->resolveBusinessTypeFilter($request);

        if ($storeParam === 'all' && $user?->isAdmin()) {
            if (! $businessTypeFilter) {
                $request->session()->forget('store_id');
            }
        }

        $storeFromParam = $this->resolveStoreParam($storeParam);

        if ($storeFromParam && $this->canSelectStore($user, $storeFromParam, $businessTypeFilter)) {
            $request->session()->put('store_id', $storeFromParam->id);
        }

        $store = null;
        $storeId = $request->session()->get('store_id');

        if ($storeId) {
            $store = $this->findStoreById((int) $storeId);
        }

        if ($store && $businessTypeFilter && $store->business_type !== $businessTypeFilter) {
            $request->session()->forget('store_id');
            $store = null;
        }

        if (! $store) {
            $store = $this->defaultStoreFor($user, $businessTypeFilter);

            if ($store) {
                $request->session()->put('store_id', $store->id);
            }
        }

        app()->instance('currentStore', $store);
        View::share('currentStore', $store);

        return $next($request);
    }

    protected function resolveStoreParam(mixed $storeParam): ?Store
    {
        if (! is_string($storeParam) || $storeParam === '' || $storeParam === 'all') {
            return null;
        }

        if (! is_numeric($storeParam)) {
            return null;
        }

        return $this->findStoreById((int) $storeParam);
    }

    protected function findStoreById(int $storeId): ?Store
    {
        try {
            return Store::query()->find($storeId);
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function defaultStoreFor(?User $user, ?string $businessTypeFilter = null): ?Store
    {
        try {
            if ($user?->isAdmin() && ! $businessTypeFilter) {
                return null;
            }

            $query = Store::query()
                ->where('is_active', true)
                ->where('is_approved', true)
                ->orderBy('id');

            if ($businessTypeFilter) {
                $query->where('business_type', $businessTypeFilter);
            }

            if ($user && ! $user->isAdmin()) {
                $query->where('owner_id', $user->id);
            }

            return $query->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function canSelectStore(?User $user, Store $store, ?string $businessTypeFilter = null): bool
    {
        if ($businessTypeFilter && $store->business_type !== $businessTypeFilter) {
            return false;
        }

        if (! $user) {
            return true;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isOwner()) {
            return $store->owner_id === $user->id;
        }

        return true;
    }

    protected function resolveBusinessTypeFilter(Request $request): ?string
    {
        if (! $request->hasSession()) {
            return null;
        }

        $filter = $request->session()->get('business_type_filter');

        return is_string($filter) && $filter !== '' ? $filter : null;
    }
}
