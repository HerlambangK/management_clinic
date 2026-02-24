@extends('layouts.dashboard')

@section('title', __('customer_package.title'))
@section('page-title', __('customer_package.title'))

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 max-sm:gap-3">
        <div>
            <p class="text-gray-500 text-sm max-sm:text-xs">{{ __('customer_package.purchase_history') }}</p>
        </div>
        <a href="{{ route('customer-packages.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 max-sm:px-3 max-sm:py-2 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm max-sm:text-xs font-medium rounded-lg transition">
            <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('customer_package.add') }}
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
        <form action="{{ route('customer-packages.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 max-sm:gap-3">
            <div class="flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="{{ __('customer_package.search_placeholder') }}"
                    class="w-full px-4 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                >
            </div>
            <div>
                <select name="status" class="w-full px-4 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition appearance-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="">{{ __('customer_package.all_status') }}</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('common.active') }}</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('customer_package.completed') }}</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>{{ __('customer_package.expired') }}</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('customer_package.cancelled') }}</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 max-sm:py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                {{ __('common.filter') }}
            </button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('customer-packages.index') }}" class="px-4 py-2 max-sm:py-1.5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 text-sm transition text-center">
                    {{ __('common.reset') }}
                </a>
            @endif
        </form>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 max-sm:px-3 max-sm:py-2 rounded-lg text-sm max-sm:text-xs">
            {{ session('success') }}
        </div>
    @endif

    <!-- Purchases List -->
    @if($customerPackages->count() > 0)
        <!-- Desktop Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hidden sm:block">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-100 dark:border-gray-600">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('customer_package.customer') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('customer_package.package') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('customer_package.sessions') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('customer_package.valid_until') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.status') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($customerPackages as $cp)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('customers.show', $cp->customer) }}" class="font-medium text-gray-900 dark:text-white hover:text-rose-600 dark:hover:text-rose-400">
                                        {{ $cp->customer->name }}
                                    </a>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $cp->customer->phone }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('packages.show', $cp->package) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:text-rose-600 dark:hover:text-rose-400">
                                        {{ $cp->package->name }}
                                    </a>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $cp->formatted_price_paid }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-20 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                            <div class="{{ $tc->accentBg ?? 'bg-rose-500' }} h-2 rounded-full" style="width: {{ $cp->usage_percentage }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $cp->sessions_used }}/{{ $cp->sessions_total }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm text-gray-900 dark:text-white">{{ format_date($cp->expires_at) }}</p>
                                    @if($cp->status === 'active')
                                        @if($cp->days_remaining <= 30)
                                            <p class="text-xs text-yellow-500 dark:text-yellow-400">{{ __('customer_package.days_remaining', ['days' => $cp->days_remaining]) }}</p>
                                        @else
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('customer_package.days_remaining', ['days' => $cp->days_remaining]) }}</p>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'active' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400',
                                            'completed' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400',
                                            'expired' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400',
                                            'cancelled' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$cp->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $cp->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('customer-packages.show', $cp) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="sm:hidden space-y-3">
            @foreach($customerPackages as $cp)
                @php
                    $statusColors = [
                        'active' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400',
                        'completed' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400',
                        'expired' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400',
                        'cancelled' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                    ];
                @endphp
                <a href="{{ route('customer-packages.show', $cp) }}" class="block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 hover:border-gray-200 dark:hover:border-gray-600 transition">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 dark:text-white text-sm truncate">{{ $cp->customer->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $cp->customer->phone }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$cp->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }} flex-shrink-0">
                            {{ $cp->status_label }}
                        </span>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 mb-3">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('customer_package.package') }}</p>
                        <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $cp->package->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $cp->formatted_price_paid }}</p>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <div class="flex-1 bg-gray-200 dark:bg-gray-600 rounded-full h-1.5">
                                    <div class="{{ $tc->accentBg ?? 'bg-rose-500' }} h-1.5 rounded-full" style="width: {{ $cp->usage_percentage }}%"></div>
                                </div>
                                <span class="text-xs text-gray-600 dark:text-gray-400">{{ $cp->sessions_used }}/{{ $cp->sessions_total }}</span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('customer_package.sessions_used_label') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-900 dark:text-white">{{ format_date($cp->expires_at) }}</p>
                            @if($cp->status === 'active')
                                @if($cp->days_remaining <= 30)
                                    <p class="text-xs text-yellow-500 dark:text-yellow-400">{{ __('customer_package.days_remaining', ['days' => $cp->days_remaining]) }}</p>
                                @else
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('customer_package.days_remaining', ['days' => $cp->days_remaining]) }}</p>
                                @endif
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($customerPackages->hasPages())
            <div class="mt-6 max-sm:mt-4">
                {{ $customerPackages->links() }}
            </div>
        @endif
    @else
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 max-sm:p-8 text-center">
            <div class="w-16 h-16 max-sm:w-12 max-sm:h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4 max-sm:mb-3">
                <svg class="w-8 h-8 max-sm:w-6 max-sm:h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <h3 class="text-lg max-sm:text-base font-medium text-gray-900 dark:text-white mb-1">{{ __('customer_package.no_customer_packages') }}</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs mb-4 max-sm:mb-3">{{ __('customer_package.no_purchases_message') }}</p>
            <a href="{{ route('customer-packages.create') }}" class="inline-flex items-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm max-sm:text-xs font-medium rounded-lg transition">
                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('customer_package.add') }}
            </a>
        </div>
    @endif
</div>
@endsection
