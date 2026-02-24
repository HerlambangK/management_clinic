@extends('layouts.dashboard')

@section('title', __('report.loyalty'))
@section('page-title', __('report.loyalty'))

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
        <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ __('report.loyalty_analysis') }}</p>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
        <form action="{{ route('reports.loyalty') }}" method="GET" class="flex flex-row max-sm:flex-col gap-4 max-sm:gap-3">
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

    <!-- Points Summary Cards -->
    <div class="grid grid-cols-4 max-lg:grid-cols-2 max-sm:grid-cols-2 gap-4 max-sm:gap-2">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.points_earned') }}</p>
            <p class="text-2xl max-sm:text-lg font-bold text-green-600 dark:text-green-400 mt-1">{{ format_number($pointsSummary['earned']) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.points_redeemed') }}</p>
            <p class="text-2xl max-sm:text-lg font-bold text-red-600 dark:text-red-400 mt-1">{{ format_number($pointsSummary['redeemed']) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.bonus_points') }}</p>
            <p class="text-2xl max-sm:text-lg font-bold text-purple-600 dark:text-purple-400 mt-1">{{ format_number($pointsSummary['bonus']) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.net_points') }}</p>
            <p class="text-2xl max-sm:text-lg font-bold text-blue-600 dark:text-blue-400 mt-1">{{ format_number($pointsSummary['earned'] - $pointsSummary['redeemed'] + $pointsSummary['bonus'] + $pointsSummary['adjusted']) }}</p>
        </div>
    </div>

    <!-- Points Chart -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('report.daily_points') }}</h3>
        <div class="h-80 max-sm:h-48">
            <canvas id="pointsChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-2 max-lg:grid-cols-1 gap-6 max-sm:gap-4">
        <!-- Top Earners -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 max-sm:px-4 max-sm:py-3 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('report.top_earners') }}</h3>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($topEarners as $index => $customer)
                    <div class="px-6 py-3 max-sm:px-4 max-sm:py-2 flex items-center justify-between">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="text-sm font-semibold text-gray-400 dark:text-gray-500 w-6">{{ $index + 1 }}</span>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $customer->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $customer->phone }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-green-600 dark:text-green-400">+{{ format_number($customer->points_earned) }}</span>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                        {{ __('report.no_data_period') }}
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Top Redeemers -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 max-sm:px-4 max-sm:py-3 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('report.top_redeemers') }}</h3>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($topRedeemers as $index => $customer)
                    <div class="px-6 py-3 max-sm:px-4 max-sm:py-2 flex items-center justify-between">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="text-sm font-semibold text-gray-400 dark:text-gray-500 w-6">{{ $index + 1 }}</span>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $customer->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $customer->phone }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-red-600 dark:text-red-400">{{ format_number($customer->points_redeemed) }}</span>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                        {{ __('report.no_data_period') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-3 max-lg:grid-cols-1 gap-6 max-sm:gap-4">
        <!-- Tier Distribution -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('report.tier_distribution') }}</h3>
            @if(count($tierDistribution) > 0)
                <div class="space-y-3">
                    @php
                        $tierColors = [
                            'bronze' => 'bg-amber-600',
                            'silver' => 'bg-gray-400',
                            'gold' => 'bg-yellow-500',
                            'platinum' => 'bg-purple-500',
                        ];
                        $total = array_sum($tierDistribution->toArray());
                    @endphp
                    @foreach($tierDistribution as $tier => $count)
                        @php $percentage = $total > 0 ? ($count / $total) * 100 : 0; @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300 capitalize">{{ $tier }}</span>
                                <span class="text-gray-500 dark:text-gray-400">{{ $count }} ({{ format_number($percentage, 1) }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="{{ $tierColors[$tier] ?? 'bg-gray-500' }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">{{ __('report.no_data_period') }}</p>
            @endif
        </div>

        <!-- Redemption Stats -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('report.redemption_status') }}</h3>
            @if(count($redemptionStats) > 0)
                <div class="space-y-3">
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-500',
                            'used' => 'bg-green-500',
                            'expired' => 'bg-gray-500',
                            'cancelled' => 'bg-red-500',
                        ];
                        $total = array_sum($redemptionStats->toArray());
                    @endphp
                    @foreach($redemptionStats as $status => $count)
                        @php $percentage = $total > 0 ? ($count / $total) * 100 : 0; @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300 capitalize">{{ __('loyalty.redemption_status_' . $status, ['default' => ucfirst($status)]) }}</span>
                                <span class="text-gray-500 dark:text-gray-400">{{ $count }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="{{ $statusColors[$status] ?? 'bg-gray-500' }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">{{ __('report.no_redemptions') }}</p>
            @endif
        </div>

        <!-- Referral Stats -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('report.referral_stats') }}</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('report.total_referrals') }}</span>
                    <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ format_number($referralStats['total']) }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <span class="text-sm text-green-600 dark:text-green-400">{{ __('report.rewarded_referrals') }}</span>
                    <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ format_number($referralStats['rewarded']) }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                    <span class="text-sm text-purple-600 dark:text-purple-400">{{ __('report.referral_points_given') }}</span>
                    <span class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ format_number($referralStats['points_given']) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('pointsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($dailyPoints->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))),
            datasets: [{
                label: '{{ __("report.earned") }}',
                data: @json($dailyPoints->pluck('earned')),
                borderColor: 'rgba(34, 197, 94, 1)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                fill: true,
                tension: 0.4,
            }, {
                label: '{{ __("report.redeemed") }}',
                data: @json($dailyPoints->pluck('redeemed')),
                borderColor: 'rgba(239, 68, 68, 1)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush
@endsection
