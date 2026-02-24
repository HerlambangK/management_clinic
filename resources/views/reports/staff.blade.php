@extends('layouts.dashboard')

@section('title', __('report.staff_performance'))
@section('page-title', __('report.staff_performance'))

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Header -->
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4 max-sm:gap-3">
        <div>
            <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition mb-2 text-sm max-sm:text-xs">
                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('report.back_to_reports') }}
            </a>
            <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ __('report.staff_performance_analysis') }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
        <form action="{{ route('reports.staff') }}" method="GET" class="flex flex-row max-sm:flex-col gap-4 max-sm:gap-3">
            <div class="max-sm:w-full">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('report.start_date') }}</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-4 py-2 max-sm:py-1.5 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition">
            </div>
            <div class="max-sm:w-full">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('report.end_date') }}</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-4 py-2 max-sm:py-1.5 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition">
            </div>
            <div class="flex items-end max-sm:w-full">
                <button type="submit" class="px-4 py-2 max-sm:py-1.5 max-sm:w-full {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm rounded-lg transition">
                    {{ __('report.apply') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-4 max-lg:grid-cols-2 max-sm:grid-cols-2 gap-4 max-sm:gap-2">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.total_staff') }}</p>
            <p class="text-2xl max-sm:text-lg font-bold text-gray-900 dark:text-gray-100 mt-1">{{ format_number($summary['total_staff']) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.total_appointments') }}</p>
            <p class="text-2xl max-sm:text-lg font-bold text-blue-600 dark:text-blue-400 mt-1">{{ format_number($summary['total_appointments']) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.total_treatments') }}</p>
            <p class="text-2xl max-sm:text-lg font-bold text-purple-600 dark:text-purple-400 mt-1">{{ format_number($summary['total_treatments']) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.total_revenue') }}</p>
            <p class="text-2xl max-sm:text-lg font-bold text-green-600 dark:text-green-400 mt-1">{{ format_currency($summary['total_revenue']) }}</p>
        </div>
    </div>

    <!-- Staff Performance Table - Desktop -->
    <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('report.staff_performance_detail') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('report.staff_name') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('report.appointments') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('report.completed') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('report.treatments_done') }}</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('report.completion_rate') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('report.revenue_generated') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($staffPerformance as $perf)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full {{ $tc->secondary ?? 'bg-rose-100 dark:bg-rose-900/30' }} flex items-center justify-center flex-shrink-0">
                                        <span class="text-sm font-semibold {{ $tc->secondaryText ?? 'text-rose-600 dark:text-rose-400' }}">{{ strtoupper(substr($perf['staff']->name, 0, 2)) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $perf['staff']->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($perf['staff']->role) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 text-center">{{ format_number($perf['appointments']) }}</td>
                            <td class="px-6 py-4 text-sm text-green-600 dark:text-green-400 text-center">{{ format_number($perf['completed']) }}</td>
                            <td class="px-6 py-4 text-sm text-purple-600 dark:text-purple-400 text-center">{{ format_number($perf['treatments']) }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $perf['completion_rate'] >= 80 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : ($perf['completion_rate'] >= 50 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400') }}">
                                    {{ $perf['completion_rate'] }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-green-600 dark:text-green-400 text-right">{{ format_currency($perf['revenue']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                {{ __('report.no_staff_data') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Staff Performance - Mobile -->
    <div class="sm:hidden space-y-3">
        @forelse($staffPerformance as $perf)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full {{ $tc->secondary ?? 'bg-rose-100 dark:bg-rose-900/30' }} flex items-center justify-center flex-shrink-0">
                        <span class="text-sm font-semibold {{ $tc->secondaryText ?? 'text-rose-600 dark:text-rose-400' }}">{{ strtoupper(substr($perf['staff']->name, 0, 2)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $perf['staff']->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($perf['staff']->role) }}</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $perf['completion_rate'] >= 80 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : ($perf['completion_rate'] >= 50 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400') }}">
                        {{ $perf['completion_rate'] }}%
                    </span>
                </div>
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $perf['appointments'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('report.appointments') }}</p>
                    </div>
                    <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <p class="text-lg font-bold text-green-600 dark:text-green-400">{{ $perf['completed'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('report.completed') }}</p>
                    </div>
                    <div class="p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <p class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ $perf['treatments'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('report.treatments') }}</p>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('report.revenue_generated') }}</span>
                    <span class="text-sm font-semibold text-green-600 dark:text-green-400">{{ format_currency($perf['revenue']) }}</span>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center">
                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('report.no_staff_data') }}</p>
            </div>
        @endforelse
    </div>

    <!-- Revenue Chart -->
    @if(count($staffPerformance) > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('report.revenue_by_staff') }}</h3>
        <div class="h-80 max-sm:h-48">
            <canvas id="staffRevenueChart"></canvas>
        </div>
    </div>
    @endif
</div>

@if(count($staffPerformance) > 0)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('staffRevenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json(collect($staffPerformance)->pluck('staff.name')),
            datasets: [{
                label: '{{ __("report.revenue") }}',
                data: @json(collect($staffPerformance)->pluck('revenue')),
                backgroundColor: 'rgba(244, 63, 94, 0.8)',
                borderRadius: 6,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endif
@endsection
