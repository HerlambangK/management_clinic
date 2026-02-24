@extends('layouts.dashboard')

@section('title', __('loyalty.customers_title'))
@section('page-title', __('loyalty.customers_title'))

@include('components.theme-classes')

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Header -->
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4 max-sm:gap-3">
        <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ __('loyalty.customers_subtitle') }}</p>
        <a href="{{ route('loyalty.index') }}" class="inline-flex items-center gap-2 px-3 py-2 max-sm:py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            {{ __('common.back') }}
        </a>
    </div>

    <!-- Tier Stats -->
    <div class="grid grid-cols-4 max-sm:grid-cols-2 gap-4">
        <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl p-4 border border-amber-200 dark:border-amber-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/50 rounded-lg flex items-center justify-center">
                    <span class="text-amber-700 dark:text-amber-400 text-lg">B</span>
                </div>
                <div>
                    <p class="text-xs text-amber-600 dark:text-amber-400">Bronze</p>
                    <p class="text-xl font-bold text-amber-700 dark:text-amber-300">{{ format_number($tierStats['bronze']) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-100 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                    <span class="text-gray-700 dark:text-gray-300 text-lg">S</span>
                </div>
                <div>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Silver</p>
                    <p class="text-xl font-bold text-gray-700 dark:text-gray-200">{{ format_number($tierStats['silver']) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-4 border border-yellow-200 dark:border-yellow-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/50 rounded-lg flex items-center justify-center">
                    <span class="text-yellow-700 dark:text-yellow-400 text-lg">G</span>
                </div>
                <div>
                    <p class="text-xs text-yellow-600 dark:text-yellow-400">Gold</p>
                    <p class="text-xl font-bold text-yellow-700 dark:text-yellow-300">{{ format_number($tierStats['gold']) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4 border border-purple-200 dark:border-purple-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center">
                    <span class="text-purple-700 dark:text-purple-400 text-lg">P</span>
                </div>
                <div>
                    <p class="text-xs text-purple-600 dark:text-purple-400">Platinum</p>
                    <p class="text-xl font-bold text-purple-700 dark:text-purple-300">{{ format_number($tierStats['platinum']) }}</p>
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

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
        <form action="{{ route('loyalty.customers') }}" method="GET" class="flex flex-row max-sm:flex-col gap-3">
            <div class="flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="w-full px-3 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition"
                    placeholder="{{ __('loyalty.search_customer') }}"
                >
            </div>
            <div class="flex gap-2">
                <div class="relative">
                    <select
                        name="tier"
                        class="w-full pl-3 pr-8 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 {{ $themeRing }} transition appearance-none"
                    >
                        <option value="">{{ __('loyalty.all_tiers') }}</option>
                        <option value="bronze" {{ request('tier') == 'bronze' ? 'selected' : '' }}>Bronze</option>
                        <option value="silver" {{ request('tier') == 'silver' ? 'selected' : '' }}>Silver</option>
                        <option value="gold" {{ request('tier') == 'gold' ? 'selected' : '' }}>Gold</option>
                        <option value="platinum" {{ request('tier') == 'platinum' ? 'selected' : '' }}>Platinum</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                <button type="submit" class="px-3 py-2 max-sm:py-1.5 bg-gray-800 dark:bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-900 dark:hover:bg-gray-500 transition">
                    {{ __('common.filter') }}
                </button>
                @if(request()->hasAny(['search', 'tier']))
                    <a href="{{ route('loyalty.customers') }}" class="px-3 py-2 max-sm:py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        {{ __('common.reset') }}
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Customers Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.customer') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.tier') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.current_points') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.lifetime_points') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('loyalty.redemptions') }}</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-5 py-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $customer->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $customer->phone }}</p>
                                </div>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $customer->loyalty_tier_color }}">
                                    {{ $customer->loyalty_tier_label }}
                                </span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ format_number($customer->loyalty_points) }}</span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ format_number($customer->lifetime_points) }}</span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ format_number($customer->loyalty_redemptions_count) }}</span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <a href="{{ route('loyalty.customer-history', $customer) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 text-sm font-medium">
                                    {{ __('common.detail') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('loyalty.no_customers') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
