@extends('layouts.dashboard')

@section('title', __('report.title'))
@section('page-title', __('report.title'))

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Header -->
    <div>
        <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ __('report.view_statistics') }}</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 max-md:grid-cols-1 gap-6 max-sm:gap-4">
        <!-- Today Stats -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <div class="flex items-center justify-between mb-4 max-sm:mb-3">
                <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('report.today') }}</h3>
                <span class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ format_date(now(), 'd M Y') }}</span>
            </div>
            <div class="grid grid-cols-2 gap-4 max-sm:gap-2">
                <div class="p-4 max-sm:p-3 bg-green-50 dark:bg-green-900/30 rounded-lg">
                    <p class="text-sm max-sm:text-xs text-green-600 dark:text-green-400">{{ __('report.revenue_label') }}</p>
                    <p class="text-xl max-sm:text-base font-bold text-green-700 dark:text-green-400">{{ format_currency($todayStats['revenue']) }}</p>
                </div>
                <div class="p-4 max-sm:p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                    <p class="text-sm max-sm:text-xs text-blue-600 dark:text-blue-400">{{ __('report.transaction_label') }}</p>
                    <p class="text-xl max-sm:text-base font-bold text-blue-700 dark:text-blue-400">{{ $todayStats['transactions_paid'] }}/{{ $todayStats['transactions'] }}</p>
                </div>
                <div class="p-4 max-sm:p-3 bg-purple-50 dark:bg-purple-900/30 rounded-lg">
                    <p class="text-sm max-sm:text-xs text-purple-600 dark:text-purple-400">{{ __('report.appointment_label') }}</p>
                    <p class="text-xl max-sm:text-base font-bold text-purple-700 dark:text-purple-400">{{ $todayStats['appointments'] }}</p>
                </div>
                <div class="p-4 max-sm:p-3 bg-rose-50 dark:bg-rose-900/30 rounded-lg">
                    <p class="text-sm max-sm:text-xs text-rose-600 dark:text-rose-400">{{ __('report.completed_label') }}</p>
                    <p class="text-xl max-sm:text-base font-bold text-rose-700 dark:text-rose-400">{{ $todayStats['appointments_completed'] }}</p>
                </div>
            </div>
        </div>

        <!-- Month Stats -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <div class="flex items-center justify-between mb-4 max-sm:mb-3">
                <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('report.this_month') }}</h3>
                <span class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ now()->format('F Y') }}</span>
            </div>
            <div class="grid grid-cols-2 gap-4 max-sm:gap-2">
                <div class="p-4 max-sm:p-3 bg-green-50 dark:bg-green-900/30 rounded-lg">
                    <p class="text-sm max-sm:text-xs text-green-600 dark:text-green-400">{{ __('report.revenue_label') }}</p>
                    <p class="text-xl max-sm:text-base font-bold text-green-700 dark:text-green-400">{{ format_currency($monthStats['revenue']) }}</p>
                </div>
                <div class="p-4 max-sm:p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                    <p class="text-sm max-sm:text-xs text-blue-600 dark:text-blue-400">{{ __('report.transaction_label') }}</p>
                    <p class="text-xl max-sm:text-base font-bold text-blue-700 dark:text-blue-400">{{ $monthStats['transactions'] }}</p>
                </div>
                <div class="p-4 max-sm:p-3 bg-purple-50 dark:bg-purple-900/30 rounded-lg">
                    <p class="text-sm max-sm:text-xs text-purple-600 dark:text-purple-400">{{ __('report.new_customer_label') }}</p>
                    <p class="text-xl max-sm:text-base font-bold text-purple-700 dark:text-purple-400">{{ $monthStats['new_customers'] }}</p>
                </div>
                <div class="p-4 max-sm:p-3 bg-rose-50 dark:bg-rose-900/30 rounded-lg">
                    <p class="text-sm max-sm:text-xs text-rose-600 dark:text-rose-400">{{ __('report.packages_sold_label') }}</p>
                    <p class="text-xl max-sm:text-base font-bold text-rose-700 dark:text-rose-400">{{ $monthStats['packages_sold'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Cards -->
    <div class="grid grid-cols-3 max-md:grid-cols-2 max-sm:grid-cols-1 gap-6 max-sm:gap-3">
        <!-- Revenue Report -->
        <a href="{{ route('reports.revenue') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4 hover:shadow-md active:bg-gray-50 dark:active:bg-gray-700 transition group">
            <div class="flex items-center gap-4 max-sm:gap-3">
                <div class="w-14 h-14 max-sm:w-12 max-sm:h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center group-hover:bg-green-200 dark:group-hover:bg-green-900/50 transition flex-shrink-0">
                    <svg class="w-7 h-7 max-sm:w-6 max-sm:h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition text-sm max-sm:text-sm">{{ __('report.revenue') }}</h3>
                    <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.revenue_analysis') }}</p>
                </div>
            </div>
            <div class="mt-4 max-sm:mt-3 flex items-center text-sm max-sm:text-xs text-rose-500 dark:text-rose-400 font-medium">
                {{ __('report.view_report') }}
                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>

        <!-- Customer Report -->
        <a href="{{ route('reports.customers') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4 hover:shadow-md active:bg-gray-50 dark:active:bg-gray-700 transition group">
            <div class="flex items-center gap-4 max-sm:gap-3">
                <div class="w-14 h-14 max-sm:w-12 max-sm:h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center group-hover:bg-purple-200 dark:group-hover:bg-purple-900/50 transition flex-shrink-0">
                    <svg class="w-7 h-7 max-sm:w-6 max-sm:h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition text-sm max-sm:text-sm">{{ __('report.customers') }}</h3>
                    <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.top_customer_growth') }}</p>
                </div>
            </div>
            <div class="mt-4 max-sm:mt-3 flex items-center text-sm max-sm:text-xs text-rose-500 dark:text-rose-400 font-medium">
                {{ __('report.view_report') }}
                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>

        <!-- Service Report -->
        <a href="{{ route('reports.services') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4 hover:shadow-md active:bg-gray-50 dark:active:bg-gray-700 transition group">
            <div class="flex items-center gap-4 max-sm:gap-3">
                <div class="w-14 h-14 max-sm:w-12 max-sm:h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-900/50 transition flex-shrink-0">
                    <svg class="w-7 h-7 max-sm:w-6 max-sm:h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition text-sm max-sm:text-sm">{{ __('report.services') }}</h3>
                    <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.popular_service_package') }}</p>
                </div>
            </div>
            <div class="mt-4 max-sm:mt-3 flex items-center text-sm max-sm:text-xs text-rose-500 dark:text-rose-400 font-medium">
                {{ __('report.view_report') }}
                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>

        <!-- Appointment Report -->
        <a href="{{ route('reports.appointments') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4 hover:shadow-md active:bg-gray-50 dark:active:bg-gray-700 transition group">
            <div class="flex items-center gap-4 max-sm:gap-3">
                <div class="w-14 h-14 max-sm:w-12 max-sm:h-12 bg-rose-100 dark:bg-rose-900/30 rounded-xl flex items-center justify-center group-hover:bg-rose-200 dark:group-hover:bg-rose-900/50 transition flex-shrink-0">
                    <svg class="w-7 h-7 max-sm:w-6 max-sm:h-6 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition text-sm max-sm:text-sm">{{ __('report.appointments') }}</h3>
                    <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.appointment_analysis') }}</p>
                </div>
            </div>
            <div class="mt-4 max-sm:mt-3 flex items-center text-sm max-sm:text-xs text-rose-500 dark:text-rose-400 font-medium">
                {{ __('report.view_report') }}
                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>

        <!-- Staff Performance Report -->
        <a href="{{ route('reports.staff') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4 hover:shadow-md active:bg-gray-50 dark:active:bg-gray-700 transition group">
            <div class="flex items-center gap-4 max-sm:gap-3">
                <div class="w-14 h-14 max-sm:w-12 max-sm:h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center group-hover:bg-amber-200 dark:group-hover:bg-amber-900/50 transition flex-shrink-0">
                    <svg class="w-7 h-7 max-sm:w-6 max-sm:h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition text-sm max-sm:text-sm">{{ __('report.staff_performance') }}</h3>
                    <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.staff_performance_analysis') }}</p>
                </div>
            </div>
            <div class="mt-4 max-sm:mt-3 flex items-center text-sm max-sm:text-xs text-rose-500 dark:text-rose-400 font-medium">
                {{ __('report.view_report') }}
                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>

        <!-- Loyalty Report -->
        @if(has_feature('loyalty'))
        <a href="{{ route('reports.loyalty') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4 hover:shadow-md active:bg-gray-50 dark:active:bg-gray-700 transition group">
            <div class="flex items-center gap-4 max-sm:gap-3">
                <div class="w-14 h-14 max-sm:w-12 max-sm:h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center group-hover:bg-yellow-200 dark:group-hover:bg-yellow-900/50 transition flex-shrink-0">
                    <svg class="w-7 h-7 max-sm:w-6 max-sm:h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition text-sm max-sm:text-sm">{{ __('report.loyalty') }}</h3>
                    <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.loyalty_analysis') }}</p>
                </div>
            </div>
            <div class="mt-4 max-sm:mt-3 flex items-center text-sm max-sm:text-xs text-rose-500 dark:text-rose-400 font-medium">
                {{ __('report.view_report') }}
                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>
        @endif

        <!-- Product Report -->
        @if(has_feature('products'))
        <a href="{{ route('reports.products') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4 hover:shadow-md active:bg-gray-50 dark:active:bg-gray-700 transition group">
            <div class="flex items-center gap-4 max-sm:gap-3">
                <div class="w-14 h-14 max-sm:w-12 max-sm:h-12 bg-cyan-100 dark:bg-cyan-900/30 rounded-xl flex items-center justify-center group-hover:bg-cyan-200 dark:group-hover:bg-cyan-900/50 transition flex-shrink-0">
                    <svg class="w-7 h-7 max-sm:w-6 max-sm:h-6 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition text-sm max-sm:text-sm">{{ __('report.products') }}</h3>
                    <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.product_analysis') }}</p>
                </div>
            </div>
            <div class="mt-4 max-sm:mt-3 flex items-center text-sm max-sm:text-xs text-rose-500 dark:text-rose-400 font-medium">
                {{ __('report.view_report') }}
                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>
        @endif
    </div>
</div>
@endsection
