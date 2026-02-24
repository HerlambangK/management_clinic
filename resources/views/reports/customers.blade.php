@extends('layouts.dashboard')

@section('title', 'Laporan Customer')
@section('page-title', 'Laporan Customer')

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Header -->
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4 max-sm:gap-3">
        <div>
            <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition mb-2 text-sm max-sm:text-xs">
                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Laporan
            </a>
            <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">Analisis customer dan pertumbuhan</p>
        </div>
        <a href="{{ route('reports.export.customers', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="inline-flex items-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 bg-green-500 text-white text-sm max-sm:text-xs font-medium rounded-lg hover:bg-green-600 transition">
            <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Export Excel
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
        <form action="{{ route('reports.customers') }}" method="GET" class="flex flex-row max-sm:flex-col gap-4 max-sm:gap-3">
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
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Total Customer</p>
            <p class="text-2xl max-sm:text-lg font-bold text-gray-900 dark:text-gray-100 mt-1">{{ format_number($totalCustomers) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Customer Baru</p>
            <p class="text-2xl max-sm:text-lg font-bold text-green-600 dark:text-green-400 mt-1">+{{ format_number($newCustomers) }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 max-sm:hidden">Periode terpilih</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Customer Aktif</p>
            <p class="text-2xl max-sm:text-lg font-bold text-blue-600 dark:text-blue-400 mt-1">{{ format_number($topCustomers->count()) }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 max-sm:hidden">Ada transaksi</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Total Belanja</p>
            <p class="text-2xl max-sm:text-lg font-bold text-rose-600 dark:text-rose-400 mt-1">{{ format_currency($topCustomers->sum('transactions_sum_total_amount')) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-3 max-lg:grid-cols-1 gap-6 max-sm:gap-4">
        <!-- Customer Growth Chart -->
        <div class="col-span-2 max-lg:col-span-1 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">Pertumbuhan Customer</h3>
            <div class="h-80 max-sm:h-48">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <!-- Gender Distribution -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">Distribusi Gender</h3>
            @if($genderStats->count() > 0)
                <div class="h-64">
                    <canvas id="genderChart"></canvas>
                </div>
                <div class="mt-4 space-y-2">
                    @php
                        $genderLabels = ['male' => 'Laki-laki', 'female' => 'Perempuan'];
                        $genderColors = ['male' => 'bg-blue-500', 'female' => 'bg-pink-500'];
                        $total = $genderStats->sum('total');
                    @endphp
                    @foreach($genderStats as $stat)
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full {{ $genderColors[$stat->gender] ?? 'bg-gray-500' }}"></div>
                                <span class="text-gray-900 dark:text-gray-100">{{ $genderLabels[$stat->gender] ?? $stat->gender }}</span>
                            </div>
                            <span class="font-medium text-gray-900 dark:text-gray-100">{{ format_number($stat->total) }} ({{ format_number($total > 0 ? ($stat->total / $total) * 100 : 0, 1) }}%)</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">Belum ada data gender</p>
            @endif
        </div>
    </div>

    <!-- Top Customers Table - Desktop -->
    <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Top 20 Customer</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('report.rank') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('customer.title') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('report.contact') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('report.transactions') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('report.total_spent') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($topCustomers as $index => $customer)
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
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $customer->name }}</p>
                                @if($customer->gender)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $customer->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $customer->phone }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $customer->email ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-gray-900 dark:text-gray-100">{{ format_number($customer->transactions_count) }}</td>
                            <td class="px-6 py-4 text-right">
                                <p class="font-semibold text-green-600 dark:text-green-400">{{ format_currency($customer->transactions_sum_total_amount ?? 0) }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('customers.show', $customer) }}" class="text-rose-500 dark:text-rose-400 hover:text-rose-600 dark:hover:text-rose-300 text-sm font-medium">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada customer dengan transaksi pada periode ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Customers - Mobile -->
    <div class="sm:hidden bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Top 20 Customer</h3>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($topCustomers as $index => $customer)
                <a href="{{ route('customers.show', $customer) }}" class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 active:bg-gray-100 dark:active:bg-gray-700">
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
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $customer->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $customer->phone }}</p>
                                </div>
                                <p class="text-sm font-semibold text-green-600 dark:text-green-400 flex-shrink-0">{{ format_currency($customer->transactions_sum_total_amount ?? 0) }}</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ format_number($customer->transactions_count) }} transaksi</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                    Tidak ada customer dengan transaksi pada periode ini
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Growth Chart
    const growthCtx = document.getElementById('growthChart').getContext('2d');
    new Chart(growthCtx, {
        type: 'line',
        data: {
            labels: @json($customerGrowth->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))),
            datasets: [{
                label: 'Customer Baru',
                data: @json($customerGrowth->pluck('count')),
                borderColor: 'rgba(244, 63, 94, 1)',
                backgroundColor: 'rgba(244, 63, 94, 0.1)',
                fill: true,
                tension: 0.4,
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
                        stepSize: 1
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

    // Gender Chart
    @if($genderStats->count() > 0)
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    new Chart(genderCtx, {
        type: 'doughnut',
        data: {
            labels: @json($genderStats->pluck('gender')->map(fn($g) => $g === 'male' ? 'Laki-laki' : 'Perempuan')),
            datasets: [{
                data: @json($genderStats->pluck('total')),
                backgroundColor: ['#3b82f6', '#ec4899'],
                borderWidth: 0,
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
            cutout: '60%'
        }
    });
    @endif
</script>
@endpush
@endsection
