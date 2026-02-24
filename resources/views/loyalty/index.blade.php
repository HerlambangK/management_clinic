@extends('layouts.dashboard')

@section('title', __('loyalty.title'))
@section('page-title', __('loyalty.title'))

@include('components.theme-classes')

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Header -->
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4 max-sm:gap-3">
        <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ __('loyalty.subtitle') }}</p>
        <div class="flex items-center gap-2">
            <a href="{{ route('loyalty.customers') }}" class="inline-flex items-center gap-2 px-3 py-2 max-sm:py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                {{ __('loyalty.customers') }}
            </a>
            <a href="{{ route('loyalty.redemptions') }}" class="inline-flex items-center gap-2 px-3 py-2 max-sm:py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                {{ __('loyalty.redemptions') }}
            </a>
            @if(config('referral.enabled', true))
            <a href="{{ route('loyalty.referrals') }}" class="inline-flex items-center gap-2 px-3 py-2 max-sm:py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                {{ __('loyalty.referrals') }}
            </a>
            @endif
            @if(auth()->user()->role === 'owner' || auth()->user()->role === 'admin')
                <a href="{{ route('loyalty.rewards.index') }}" class="inline-flex items-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 {{ $themeButton }} text-white text-sm max-sm:text-xs font-medium rounded-lg transition">
                    <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('loyalty.manage_rewards') }}
                </a>
            @endif
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-3 max-sm:grid-cols-1 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 max-sm:p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('loyalty.total_earned') }}</p>
                    <p class="text-2xl max-sm:text-xl font-bold text-gray-900 dark:text-white mt-1">{{ format_number($stats['total_earned']) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 max-sm:p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('loyalty.total_redeemed') }}</p>
                    <p class="text-2xl max-sm:text-xl font-bold text-gray-900 dark:text-white mt-1">{{ format_number($stats['total_redeemed']) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 max-sm:p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('loyalty.active_customers') }}</p>
                    <p class="text-2xl max-sm:text-xl font-bold text-gray-900 dark:text-white mt-1">{{ format_number($stats['active_customers']) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 max-sm:px-3 max-sm:py-2 rounded-lg text-sm max-sm:text-xs">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 max-sm:px-3 max-sm:py-2 rounded-lg text-sm max-sm:text-xs">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
        <form action="{{ route('loyalty.index') }}" method="GET" class="space-y-3">
            <div class="flex flex-row max-sm:flex-col gap-3">
                <div class="flex-1">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="w-full px-3 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition"
                        placeholder="{{ __('loyalty.search_customer') }}"
                    >
                </div>
                <div class="relative max-sm:w-full">
                    <select
                        name="type"
                        class="w-full pl-3 pr-8 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition appearance-none"
                    >
                        <option value="">{{ __('loyalty.all_types') }}</option>
                        @foreach(\App\Models\LoyaltyPoint::TYPES as $value => $label)
                            <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="flex flex-row max-sm:flex-col gap-2">
                <div class="flex gap-2 flex-1 max-sm:flex-col">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="flex-1 px-3 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="flex-1 px-3 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 max-sm:flex-none px-3 py-2 max-sm:py-1.5 bg-gray-800 dark:bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-900 dark:hover:bg-gray-500 transition">
                        {{ __('common.filter') }}
                    </button>
                    @if(request()->hasAny(['search', 'type', 'date_from', 'date_to']))
                        <a href="{{ route('loyalty.index') }}" class="px-3 py-2 max-sm:py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition text-center">
                            {{ __('common.reset') }}
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Points History Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <!-- Desktop Table -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.date') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.customer') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.type') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.points') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.balance') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.description') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($points as $point)
                        @php
                            $typeColors = [
                                'earn' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                'redeem' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                'expire' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                'adjust' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ format_datetime($point->created_at) }}</span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <a href="{{ route('loyalty.customer-history', $point->customer) }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $point->customer?->name ?? '-' }}
                                </a>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $typeColors[$point->type] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $point->type_label }}
                                </span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-sm font-medium {{ $point->points > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $point->points > 0 ? '+' : '' }}{{ format_number($point->points) }}
                                </span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-sm text-gray-900 dark:text-white">{{ format_number($point->balance_after) }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $point->description ?? '-' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('loyalty.no_history') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="sm:hidden divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($points as $point)
                @php
                    $typeColors = [
                        'earn' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                        'redeem' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                        'expire' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                        'adjust' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                    ];
                @endphp
                <a href="{{ route('loyalty.customer-history', $point->customer) }}" class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 active:bg-gray-100 dark:active:bg-gray-700">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $point->customer?->name ?? '-' }}</span>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $typeColors[$point->type] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $point->type_label }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $point->description ?? '-' }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ format_datetime($point->created_at) }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-sm font-semibold {{ $point->points > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $point->points > 0 ? '+' : '' }}{{ format_number($point->points) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('loyalty.balance') }}: {{ format_number($point->balance_after) }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-8 text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('loyalty.no_history') }}</p>
                </div>
            @endforelse
        </div>

        @if($points->hasPages())
            <div class="px-5 py-4 max-sm:px-4 max-sm:py-3 border-t border-gray-100 dark:border-gray-700">
                {{ $points->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
