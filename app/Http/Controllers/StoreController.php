<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $filters = [
            'business_type' => $request->string('business_type')->toString(),
            'status' => $request->string('status')->toString(),
            'search' => $request->string('search')->toString(),
        ];

        if ($request->hasSession()) {
            $sessionFilter = $request->session()->get('business_type_filter');
            if (is_string($sessionFilter) && $sessionFilter !== '') {
                $filters['business_type'] = $sessionFilter;
            }
        }

        $query = Store::query()
            ->with(['owner', 'approver'])
            ->latest();

        if (! $user->isAdmin()) {
            $query->where('owner_id', $user->id);
        }

        if ($filters['business_type']) {
            $query->where('business_type', $filters['business_type']);
        }

        if ($filters['status']) {
            match ($filters['status']) {
                'approved' => $query->where('is_approved', true),
                'pending' => $query->where('is_approved', false),
                'inactive' => $query->where('is_active', false),
                'active' => $query->where('is_active', true),
                default => null,
            };
        }

        if ($filters['search']) {
            $query->where('name', 'like', '%'.$filters['search'].'%');
        }

        $stores = $query->paginate(15)->withQueryString();
        $businessTypes = config('business.types');

        return view('stores.index', compact('stores', 'filters', 'businessTypes'));
    }

    public function create(): View
    {
        $user = request()->user();
        $businessTypes = config('business.types');
        $owners = collect();

        if ($user->isAdmin()) {
            $owners = User::query()
                ->where('role', 'owner')
                ->orderBy('name')
                ->get();
        }

        return view('stores.create', compact('businessTypes', 'owners'));
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'business_type' => $validated['business_type'],
            'is_active' => true,
            'is_approved' => false,
        ];

        if ($user->isAdmin()) {
            $data['owner_id'] = $validated['owner_id'];
            $data['is_active'] = $request->boolean('is_active', true);
            $data['is_approved'] = $request->boolean('is_approved');

            if ($data['is_approved']) {
                $data['approved_by'] = $user->id;
                $data['approved_at'] = now();
            }
        } else {
            $data['owner_id'] = $user->id;
        }

        $owner = User::query()->find($data['owner_id']);

        if ($owner && ! $owner->business_type) {
            $owner->update(['business_type' => $data['business_type']]);
        }

        Store::create($data);

        return redirect()->route('stores.index')
            ->with('success', __('store.created'));
    }

    public function edit(Store $store): View
    {
        $this->authorizeStoreAccess($store);

        $businessTypes = config('business.types');
        $owners = collect();

        if (request()->user()->isAdmin()) {
            $owners = User::query()
                ->where('role', 'owner')
                ->orderBy('name')
                ->get();
        }

        return view('stores.edit', compact('store', 'businessTypes', 'owners'));
    }

    public function update(StoreRequest $request, Store $store): RedirectResponse
    {
        $this->authorizeStoreAccess($store);

        $user = $request->user();
        $validated = $request->validated();

        if ($user->isAdmin()) {
            $data = [
                'name' => $validated['name'],
                'business_type' => $validated['business_type'],
                'owner_id' => $validated['owner_id'],
                'is_active' => $request->boolean('is_active'),
                'is_approved' => $request->boolean('is_approved'),
            ];

            if ($data['is_approved']) {
                if (! $store->is_approved) {
                    $data['approved_by'] = $user->id;
                    $data['approved_at'] = now();
                }
            } else {
                $data['approved_by'] = null;
                $data['approved_at'] = null;
            }

            $store->update($data);

            $owner = User::query()->find($data['owner_id']);

            if ($owner && ! $owner->business_type) {
                $owner->update(['business_type' => $data['business_type']]);
            }
        } else {
            $store->update([
                'name' => $validated['name'],
            ]);
        }

        return redirect()->route('stores.index')
            ->with('success', __('store.updated'));
    }

    protected function authorizeStoreAccess(Store $store): void
    {
        $user = request()->user();

        if ($user->isAdmin()) {
            return;
        }

        if ($store->owner_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke store ini.');
        }
    }
}
