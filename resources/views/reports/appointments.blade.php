@extends('layouts.dashboard')

@section('title', __('report.appointments'))
@section('page-title', __('report.appointments'))

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
            <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ __('report.appointment_analysis') }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
        <form action="{{ route('reports.appointments') }}" method="GET" class="flex flex-row max-sm:flex-col gap-4 max-sm:gap-3">
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
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.total_appointments') }}</p>
            <p class="text-2xl max-sm:text-lg font-bold text-gray-900 dark:text-gray-100 mt-1">{{ format_number($summary['total']) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.completed') }}</p>
            <p class="text-2xl max-sm:text-lg font-bold text-green-600 dark:text-green-400 mt-1">{{ format_number($summary['completed']) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.cancelled') }}</p>
            <p class="text-2xl max-sm:text-lg font-bold text-red-600 dark:text-red-400 mt-1">{{ format_number($summary['cancelled']) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 max-sm:p-3 shadow-sm border border-gray-100 dark:border-gray-700">
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('report.completion_rate') }}</p>
            <p class="text-2xl max-sm:text-lg font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $completionRate }}%</p>
        </div>
    </div>

    <div class="grid grid-cols-2 max-lg:grid-cols-1 gap-6 max-sm:gap-4">
        <!-- Status Breakdown -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('report.status_breakdown') }}</h3>
            @if(count($statusBreakdown) > 0)
                <div class="space-y-3">
                    @php
                        $statusColors = [
                            'pending' => 'bg-gray-500',
                            'confirmed' => 'bg-yellow-500',
                            'in_progress' => 'bg-blue-500',
                            'completed' => 'bg-green-500',
                            'cancelled' => 'bg-red-500',
                            'no_show' => 'bg-red-400',
                        ];
                        $total = array_sum($statusBreakdown->toArray());
                    @endphp
                    @foreach($statusBreakdown as $status => $count)
                        @php $percentage = $total > 0 ? ($count / $total) * 100 : 0; @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ \App\Models\Appointment::STATUSES[$status] ?? ucfirst($status) }}</span>
                                <span class="text-gray-500 dark:text-gray-400">{{ $count }} ({{ format_number($percentage, 1) }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="{{ $statusColors[$status] ?? 'bg-gray-500' }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">{{ __('report.no_data_period') }}</p>
            @endif
        </div>

        <!-- Source Breakdown -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('report.source_breakdown') }}</h3>
            @if(count($sourceBreakdown) > 0)
                <div class="space-y-3">
                    @php
                        $sourceColors = [
                            'walk_in' => 'bg-purple-500',
                            'phone' => 'bg-blue-500',
                            'whatsapp' => 'bg-green-500',
                            'online' => 'bg-rose-500',
                        ];
                        $total = array_sum($sourceBreakdown->toArray());
                    @endphp
                    @foreach($sourceBreakdown as $source => $count)
                        @php $percentage = $total > 0 ? ($count / $total) * 100 : 0; @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ \App\Models\Appointment::SOURCES[$source] ?? ucfirst($source) }}</span>
                                <span class="text-gray-500 dark:text-gray-400">{{ $count }} ({{ format_number($percentage, 1) }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="{{ $sourceColors[$source] ?? 'bg-gray-500' }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">{{ __('report.no_data_period') }}</p>
            @endif
        </div>
    </div>

    <!-- Daily Chart -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('report.daily_appointments') }}</h3>
        <div class="h-80 max-sm:h-48">
            <canvas id="appointmentChart"></canvas>
        </div>
    </div>

    <!-- Peak Hours -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('report.peak_hours') }}</h3>
        @if($peakHours->count() > 0)
            <div class="h-64 max-sm:h-48">
                <canvas id="peakHoursChart"></canvas>
            </div>
        @else
            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">{{ __('report.no_data_period') }}</p>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Daily appointments chart
    const ctx = document.getElementById('appointmentChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($dailyData->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))),
            datasets: [{
                label: '{{ __("report.total") }}',
                data: @json($dailyData->pluck('total')),
                backgroundColor: 'rgba(244, 63, 94, 0.8)',
                borderRadius: 6,
            }, {
                label: '{{ __("report.completed") }}',
                data: @json($dailyData->pluck('completed')),
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                borderRadius: 6,
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

    // Peak hours chart
    @if($peakHours->count() > 0)
    const ctx2 = document.getElementById('peakHoursChart').getContext('2d');
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: @json($peakHourLabels),
            datasets: [{
                label: '{{ __("report.appointments") }}',
                data: @json($peakHours->pluck('count')),
                borderColor: 'rgba(244, 63, 94, 1)',
                backgroundColor: 'rgba(244, 63, 94, 0.1)',
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
    @endif
</script>
@endpush
@endsection
