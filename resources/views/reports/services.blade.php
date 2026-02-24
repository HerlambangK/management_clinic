@extends('layouts.dashboard')

@section('title', __('report.services'))
@section('page-title', __('report.services'))

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
            <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ __('report.service_performance') }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
        <form action="{{ route('reports.services') }}" method="GET" class="flex flex-row max-sm:flex-col gap-4 max-sm:gap-3">
            <div class="max-sm:w-full">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal Mulai</label>
                <input
                    type="date"
                    name="start_date"
                    value="{{ $startDate }}"
                    class="w-full px-4 py-2 max-sm:py-1.5 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition"
                >
            </div>
            <div class="max-sm:w-full">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal Akhir</label>
                <input
                    type="date"
                    name="end_date"
                    value="{{ $endDate }}"
                    class="w-full px-4 py-2 max-sm:py-1.5 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition"
                >
            </div>
            <div class="flex items-end max-sm:w-full">
                <button type="submit" class="px-4 py-2 max-sm:py-1.5 max-sm:w-full {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm rounded-lg transition">
                    Terapkan
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-4 max-lg:grid-cols-2 max-sm:grid-cols-2 gap-4 max-sm:gap-2">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Total Layanan</p>
            <p class="text-2xl max-sm:text-lg font-bold text-gray-900 dark:text-gray-100 mt-1">{{ format_number($popularServices->count()) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Pendapatan Layanan</p>
            <p class="text-2xl max-sm:text-lg font-bold text-blue-600 dark:text-blue-400 mt-1">{{ format_currency($totalServiceRevenue) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Paket Terjual</p>
            <p class="text-2xl max-sm:text-lg font-bold text-purple-600 dark:text-purple-400 mt-1">{{ format_number($packageSales->sum('sold')) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Pendapatan Paket</p>
            <p class="text-2xl max-sm:text-lg font-bold text-green-600 dark:text-green-400 mt-1">{{ format_currency($totalPackageRevenue) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 max-lg:grid-cols-1 gap-6 max-sm:gap-4">
        <!-- Service Appointments Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">Layanan Terpopuler (Booking)</h3>
            <div class="h-80 max-sm:h-48">
                <canvas id="servicesChart"></canvas>
            </div>
        </div>

        <!-- Revenue Distribution -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">Distribusi Pendapatan</h3>
            <div class="h-80 max-sm:h-48">
                <canvas id="revenueDistChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Service Revenue Table - Desktop -->
    <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Pendapatan per Layanan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Layanan</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Booking</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Terjual</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($serviceRevenue as $index => $service)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4">
                                @if($index < 3)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $index === 0 ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400' : ($index === 1 ? 'bg-gray-200 text-gray-700 dark:bg-gray-600 dark:text-gray-300' : 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-400') }} font-bold text-sm">
                                        {{ $index + 1 }}
                                    </span>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400 pl-2">{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $service->item_name }}</p>
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-gray-500 dark:text-gray-400">
                                @php
                                    $booking = $popularServices->firstWhere('id', $service->service_id);
                                @endphp
                                {{ $booking ? $booking->appointments_count : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-gray-900 dark:text-gray-100">{{ format_number($service->qty) }}</td>
                            <td class="px-6 py-4 text-right">
                                <p class="font-semibold text-green-600 dark:text-green-400">{{ format_currency($service->revenue) }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada data layanan terjual pada periode ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($serviceRevenue->count() > 0)
                    <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('common.total') }}</td>
                            <td class="px-6 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100 text-right">{{ format_number($serviceRevenue->sum('qty')) }}</td>
                            <td class="px-6 py-3 text-sm font-bold text-green-600 dark:text-green-400 text-right">{{ format_currency($totalServiceRevenue) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Service Revenue Table - Mobile -->
    <div class="sm:hidden bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Pendapatan per Layanan</h3>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($serviceRevenue as $index => $service)
                <div class="p-4">
                    <div class="flex items-start gap-3">
                        @if($index < 3)
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full {{ $index === 0 ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400' : ($index === 1 ? 'bg-gray-200 text-gray-700 dark:bg-gray-600 dark:text-gray-300' : 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-400') }} font-bold text-xs flex-shrink-0">
                                {{ $index + 1 }}
                            </span>
                        @else
                            <span class="w-7 h-7 flex items-center justify-center text-xs text-gray-500 dark:text-gray-400 flex-shrink-0">{{ $index + 1 }}</span>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $service->item_name }}</p>
                                <p class="text-sm font-semibold text-green-600 dark:text-green-400 flex-shrink-0">{{ format_currency($service->revenue) }}</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ format_number($service->qty) }} terjual</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                    Tidak ada data layanan terjual pada periode ini
                </div>
            @endforelse
        </div>
        @if($serviceRevenue->count() > 0)
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Total ({{ format_number($serviceRevenue->sum('qty')) }} terjual)</span>
                    <span class="text-sm font-bold text-green-600 dark:text-green-400">{{ format_currency($totalServiceRevenue) }}</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Package Sales Table - Desktop -->
    <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Penjualan Paket</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Paket</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Terjual</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($packageSales as $index => $package)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4">
                                @if($index < 3)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $index === 0 ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400' : ($index === 1 ? 'bg-gray-200 text-gray-700 dark:bg-gray-600 dark:text-gray-300' : 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-400') }} font-bold text-sm">
                                        {{ $index + 1 }}
                                    </span>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400 pl-2">{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $pkg = \App\Models\Package::find($package->package_id);
                                @endphp
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $pkg ? $pkg->name : 'Paket Dihapus' }}</p>
                                @if($pkg)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $pkg->total_sessions }} sesi</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-gray-900 dark:text-gray-100">{{ format_number($package->sold) }}</td>
                            <td class="px-6 py-4 text-right">
                                <p class="font-semibold text-purple-600 dark:text-purple-400">{{ format_currency($package->revenue) }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada paket terjual pada periode ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($packageSales->count() > 0)
                    <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <td colspan="2" class="px-6 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('common.total') }}</td>
                            <td class="px-6 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100 text-right">{{ format_number($packageSales->sum('sold')) }}</td>
                            <td class="px-6 py-3 text-sm font-bold text-purple-600 dark:text-purple-400 text-right">{{ format_currency($totalPackageRevenue) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Package Sales Table - Mobile -->
    <div class="sm:hidden bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Penjualan Paket</h3>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($packageSales as $index => $package)
                @php
                    $pkg = \App\Models\Package::find($package->package_id);
                @endphp
                <div class="p-4">
                    <div class="flex items-start gap-3">
                        @if($index < 3)
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full {{ $index === 0 ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400' : ($index === 1 ? 'bg-gray-200 text-gray-700 dark:bg-gray-600 dark:text-gray-300' : 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-400') }} font-bold text-xs flex-shrink-0">
                                {{ $index + 1 }}
                            </span>
                        @else
                            <span class="w-7 h-7 flex items-center justify-center text-xs text-gray-500 dark:text-gray-400 flex-shrink-0">{{ $index + 1 }}</span>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $pkg ? $pkg->name : 'Paket Dihapus' }}</p>
                                    @if($pkg)
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ format_number($pkg->total_sessions) }} sesi</p>
                                    @endif
                                </div>
                                <p class="text-sm font-semibold text-purple-600 dark:text-purple-400 flex-shrink-0">{{ format_currency($package->revenue) }}</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ format_number($package->sold) }} terjual</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                    Tidak ada paket terjual pada periode ini
                </div>
            @endforelse
        </div>
        @if($packageSales->count() > 0)
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Total ({{ format_number($packageSales->sum('sold')) }} terjual)</span>
                    <span class="text-sm font-bold text-purple-600 dark:text-purple-400">{{ format_currency($totalPackageRevenue) }}</span>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Services Chart
    const servicesCtx = document.getElementById('servicesChart').getContext('2d');
    new Chart(servicesCtx, {
        type: 'bar',
        data: {
            labels: @json($popularServices->take(10)->pluck('name')),
            datasets: [{
                label: 'Booking',
                data: @json($popularServices->take(10)->pluck('appointments_count')),
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Revenue Distribution Chart
    const revenueDistCtx = document.getElementById('revenueDistChart').getContext('2d');
    new Chart(revenueDistCtx, {
        type: 'doughnut',
        data: {
            labels: ['Layanan', 'Paket'],
            datasets: [{
                data: [{{ $totalServiceRevenue }}, {{ $totalPackageRevenue }}],
                backgroundColor: ['rgba(59, 130, 246, 0.8)', 'rgba(168, 85, 247, 0.8)'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            cutout: '60%'
        }
    });
</script>
@endpush
@endsection
