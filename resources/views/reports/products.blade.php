@extends('layouts.dashboard')

@section('title', __('report.products'))
@section('page-title', __('report.products'))

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Header -->
    <div>
        <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition mb-2 text-sm max-sm:text-xs">
            <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            {{ __('report.back_to_reports') }}
        </a>
        <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ __('report.product_analysis') }}</p>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
        <form action="{{ route('reports.products') }}" method="GET" class="flex flex-row max-sm:flex-col gap-4 max-sm:gap-3">
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
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.total_products') }}</p>
            <p class="text-2xl max-sm:text-lg font-bold text-gray-900 dark:text-gray-100 mt-1">{{ format_number($summary['total_products']) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $summary['active_products'] }} {{ __('common.active') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.qty_sold') }}</p>
            <p class="text-2xl max-sm:text-lg font-bold text-blue-600 dark:text-blue-400 mt-1">{{ format_number($summary['total_sold']) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.product_revenue') }}</p>
            <p class="text-2xl max-sm:text-lg font-bold text-green-600 dark:text-green-400 mt-1">{{ format_currency($summary['total_revenue']) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.stock_value') }}</p>
            <p class="text-2xl max-sm:text-lg font-bold text-purple-600 dark:text-purple-400 mt-1">{{ format_currency($stockValue) }}</p>
        </div>
    </div>

    <!-- Alerts -->
    @if($outOfStock->count() > 0 || $lowStockProducts->count() > 0)
    <div class="grid grid-cols-2 max-lg:grid-cols-1 gap-4">
        @if($outOfStock->count() > 0)
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h4 class="font-semibold text-red-800 dark:text-red-400">{{ __('report.out_of_stock') }} ({{ $outOfStock->count() }})</h4>
            </div>
            <div class="space-y-1">
                @foreach($outOfStock->take(5) as $product)
                    <p class="text-sm text-red-700 dark:text-red-300">{{ $product->name }}</p>
                @endforeach
                @if($outOfStock->count() > 5)
                    <p class="text-xs text-red-600 dark:text-red-400">{{ __('common.and_more', ['count' => $outOfStock->count() - 5]) }}</p>
                @endif
            </div>
        </div>
        @endif

        @if($lowStockProducts->count() > 0)
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h4 class="font-semibold text-yellow-800 dark:text-yellow-400">{{ __('report.low_stock') }} ({{ $lowStockProducts->count() }})</h4>
            </div>
            <div class="space-y-1">
                @foreach($lowStockProducts->take(5) as $product)
                    <p class="text-sm text-yellow-700 dark:text-yellow-300">{{ $product->name }} <span class="text-yellow-600 dark:text-yellow-400">({{ $product->stock }} {{ __('common.left') }})</span></p>
                @endforeach
                @if($lowStockProducts->count() > 5)
                    <p class="text-xs text-yellow-600 dark:text-yellow-400">{{ __('common.and_more', ['count' => $lowStockProducts->count() - 5]) }}</p>
                @endif
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Sales Chart -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('report.daily_product_sales') }}</h3>
        <div class="h-80 max-sm:h-48">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Product Sales Table - Desktop -->
    <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('report.product_sales') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('report.rank') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('report.product_name') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('report.qty_sold') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('report.revenue') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($productSales as $index => $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item->item_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 text-right">{{ format_number($item->qty_sold) }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-green-600 dark:text-green-400 text-right">{{ format_currency($item->revenue) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                {{ __('report.no_product_sales') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($productSales->count() > 0)
                    <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <td colspan="2" class="px-6 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('common.total') }}</td>
                            <td class="px-6 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100 text-right">{{ format_number($productSales->sum('qty_sold')) }}</td>
                            <td class="px-6 py-3 text-sm font-bold text-green-600 dark:text-green-400 text-right">{{ format_currency($productSales->sum('revenue')) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Product Sales - Mobile -->
    <div class="sm:hidden bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('report.product_sales') }}</h3>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($productSales as $index => $item)
                <div class="p-4">
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="text-xs font-semibold text-gray-400 dark:text-gray-500">{{ $index + 1 }}.</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $item->item_name }}</span>
                        </div>
                        <span class="text-sm font-semibold text-green-600 dark:text-green-400">{{ format_currency($item->revenue) }}</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 ml-5">{{ format_number($item->qty_sold) }} {{ __('common.sold') }}</p>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                    {{ __('report.no_product_sales') }}
                </div>
            @endforelse
        </div>
        @if($productSales->count() > 0)
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('common.total') }} ({{ format_number($productSales->sum('qty_sold')) }} pcs)</span>
                    <span class="text-sm font-bold text-green-600 dark:text-green-400">{{ format_currency($productSales->sum('revenue')) }}</span>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($dailySales->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))),
            datasets: [{
                label: '{{ __("report.revenue") }}',
                data: @json($dailySales->pluck('revenue')),
                backgroundColor: 'rgba(244, 63, 94, 0.8)',
                borderRadius: 6,
                yAxisID: 'y',
            }, {
                label: '{{ __("report.qty_sold") }}',
                data: @json($dailySales->pluck('qty')),
                type: 'line',
                borderColor: 'rgba(59, 130, 246, 1)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: false,
                tension: 0.4,
                yAxisID: 'y1',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false,
                    },
                },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush
@endsection
