@extends('layouts.dashboard')

@section('title', __('store.title'))
@section('page-title', __('store.title'))

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4 max-sm:gap-3">
        <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ __('store.subtitle') }}</p>
        <a href="{{ route('stores.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 {{ $tc->button }} text-white text-sm max-sm:text-xs font-medium rounded-lg transition">
            <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('store.add') }}
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
        <form action="{{ route('stores.index') }}" method="GET" class="flex flex-row max-sm:flex-col gap-3">
            <div class="flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ $filters['search'] ?? '' }}"
                    placeholder="{{ __('store.search_placeholder') }}"
                    class="w-full px-3 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring }} transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"
                >
            </div>
            <div class="flex gap-2 max-sm:flex-col">
                <div class="relative">
                    <select name="business_type" class="w-full pl-3 pr-8 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring }} transition appearance-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">{{ __('store.all_business_types') }}</option>
                        @foreach($businessTypes as $key => $config)
                            <option value="{{ $key }}" {{ ($filters['business_type'] ?? '') === $key ? 'selected' : '' }}>
                                {{ $config['name'] ?? $key }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                <div class="relative">
                    <select name="status" class="w-full pl-3 pr-8 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring }} transition appearance-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">{{ __('store.all_status') }}</option>
                        <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>{{ __('common.active') }}</option>
                        <option value="inactive" {{ ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>{{ __('common.inactive') }}</option>
                        <option value="approved" {{ ($filters['status'] ?? '') === 'approved' ? 'selected' : '' }}>{{ __('store.approved') }}</option>
                        <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>{{ __('store.pending') }}</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                <button type="submit" class="px-3 py-2 max-sm:py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    {{ __('common.filter') }}
                </button>
                @if(request()->hasAny(['search', 'business_type', 'status']))
                    <a href="{{ route('stores.index') }}" class="px-3 py-2 max-sm:py-1.5 text-gray-500 dark:text-gray-400 text-sm font-medium hover:text-gray-700 dark:hover:text-gray-200 transition">
                        {{ __('common.reset') }}
                    </a>
                @endif
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 max-sm:px-3 max-sm:py-2 rounded-lg text-sm max-sm:text-xs">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 max-sm:px-3 max-sm:py-2 rounded-lg text-sm max-sm:text-xs">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('store.name') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('store.business_type') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('store.owner') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('store.status') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('store.approval') }}</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($stores as $store)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $store->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">#{{ $store->id }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 capitalize">{{ $businessTypes[$store->business_type]['name'] ?? $store->business_type }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $store->owner?->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($store->is_active)
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300">{{ __('common.active') }}</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ __('common.inactive') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($store->is_approved)
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">{{ __('store.approved') }}</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300">{{ __('store.pending') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('stores.edit', $store) }}" class="text-sm {{ $tc->link ?? 'text-rose-600' }} hover:underline">
                                    {{ __('common.edit') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                {{ __('store.empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $stores->links() }}
        </div>
    </div>
</div>
@endsection
