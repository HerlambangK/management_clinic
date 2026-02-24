@extends('layouts.dashboard')

@section('title', __('report.revenue'))
@section('page-title', __('report.revenue'))

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
            <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ __('report.revenue_analysis') }}</p>
        </div>
        <a href="{{ route('reports.export.revenue', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="inline-flex items-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 bg-green-500 text-white text-sm max-sm:text-xs font-medium rounded-lg hover:bg-green-600 transition">
            <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            {{ __('common.export') }} Excel
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
        <form action="{{ route('reports.revenue') }}" method="GET" class="flex flex-row max-sm:flex-col gap-4 max-sm:gap-3">
            <div class="max-sm:w-full">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('report.period') }}</label>
                <select name="period" class="w-full px-4 py-2 max-sm:py-1.5 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition">
                    <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>{{ __('report.daily') }}</option>
                    <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>{{ __('report.monthly') }}</option>
                </select>
            </div>
            <div class="max-sm:w-full">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('report.start_date') }}</label>
                <input
                    type="date"
                    name="start_date"
                    value="{{ $startDate }}"
                    class="w-full px-4 py-2 max-sm:py-1.5 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition"
                >
            </div>
            <div class="max-sm:w-full">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('report.end_date') }}</label>
                <input
                    type="date"
                    name="end_date"
                    value="{{ $endDate }}"
                    class="w-full px-4 py-2 max-sm:py-1.5 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition"
                >
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
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.total_revenue') }}</p>
            <p class="text-2xl max-sm:text-lg font-bold text-green-600 dark:text-green-400 mt-1">{{ format_currency($summary['total_revenue']) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.total_transactions') }}</p>
            <p class="text-2xl max-sm:text-lg font-bold text-gray-900 dark:text-gray-100 mt-1">{{ format_number($summary['total_transactions']) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.average_transaction') }}</p>
            <p class="text-2xl max-sm:text-lg font-bold text-gray-900 dark:text-gray-100 mt-1">{{ format_currency($summary['average_transaction']) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.total_discount') }}</p>
            <p class="text-2xl max-sm:text-lg font-bold text-red-600 dark:text-red-400 mt-1">{{ format_currency($summary['total_discount']) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-3 max-lg:grid-cols-1 gap-6 max-sm:gap-4">
        <!-- Revenue Chart -->
        <div class="col-span-2 max-lg:col-span-1 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('report.revenue_chart') }}</h3>
            <div class="h-80 max-sm:h-48">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('report.payment_methods') }}</h3>
            @if($paymentMethods->count() > 0)
                <div class="space-y-4">
                    @php
                        $totalPayments = $paymentMethods->sum('total');
                        $colors = ['bg-green-500', 'bg-blue-500', 'bg-purple-500', 'bg-yellow-500', 'bg-rose-500', 'bg-gray-500'];
                    @endphp
                    @foreach($paymentMethods as $index => $method)
                        @php
                            $percentage = $totalPayments > 0 ? ($method->total / $totalPayments) * 100 : 0;
                            $label = \App\Models\Transaction::PAYMENT_METHODS[$method->payment_method] ?? $method->payment_method;
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                <span class="text-gray-500 dark:text-gray-400">{{ format_currency($method->total) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="{{ $colors[$index % count($colors)] }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ format_number($percentage, 1) }}%</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">{{ __('report.no_payment_data') }}</p>
            @endif
        </div>
    </div>

    <!-- Revenue Table - Desktop -->
    <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('report.revenue_by_period', ['period' => $period === 'daily' ? __('report.daily_detail') : __('report.monthly_detail')]) }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ $period === 'daily' ? __('common.date') : __('report.this_month') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('report.transactions') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('report.revenue') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('report.average_transaction') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($data as $row)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                @if($period === 'daily')
                                    {{ format_date($row->date, 'd M Y') }}
                                @else
                                    {{ format_date($row->month . '-01', 'F Y') }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 text-right">{{ format_number($row->transactions) }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-green-600 dark:text-green-400 text-right">{{ format_currency($row->revenue) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 text-right">{{ format_currency($row->transactions > 0 ? $row->revenue / $row->transactions : 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                {{ __('report.no_data_period') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($data->count() > 0)
                    <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <td class="px-6 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('common.total') }}</td>
                            <td class="px-6 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100 text-right">{{ format_number($data->sum('transactions')) }}</td>
                            <td class="px-6 py-3 text-sm font-bold text-green-600 dark:text-green-400 text-right">{{ format_currency($data->sum('revenue')) }}</td>
                            <td class="px-6 py-3 text-sm text-gray-500 dark:text-gray-400 text-right">-</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Revenue Table - Mobile -->
    <div class="sm:hidden bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('report.revenue_by_period', ['period' => $period === 'daily' ? __('report.daily_detail') : __('report.monthly_detail')]) }}</h3>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($data as $row)
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            @if($period === 'daily')
                                {{ format_date($row->date, 'd M Y') }}
                            @else
                                {{ format_date($row->month . '-01', 'M Y') }}
                            @endif
                        </span>
                        <span class="text-sm font-semibold text-green-600 dark:text-green-400">{{ format_currency($row->revenue) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                        <span>{{ format_number($row->transactions) }} transaksi</span>
                        <span>Avg: {{ format_currency($row->transactions > 0 ? $row->revenue / $row->transactions : 0) }}</span>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                    {{ __('report.no_data_period') }}
                </div>
            @endforelse
        </div>
        @if($data->count() > 0)
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('common.total') }} ({{ format_number($data->sum('transactions')) }} trx)</span>
                    <span class="text-sm font-bold text-green-600 dark:text-green-400">{{ format_currency($data->sum('revenue')) }}</span>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($data->pluck($period === 'daily' ? 'date' : 'month')->map(function($d) use ($period) {
                return $period === 'daily'
                    ? \Carbon\Carbon::parse($d)->format('d M')
                    : \Carbon\Carbon::parse($d . '-01')->format('M Y');
            })),
            datasets: [{
                label: '{{ __("report.revenue") }}',
                data: @json($data->pluck('revenue')),
                backgroundColor: 'rgba(244, 63, 94, 0.8)',
                borderColor: 'rgba(244, 63, 94, 1)',
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
