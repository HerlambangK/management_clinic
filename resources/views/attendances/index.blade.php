@extends('layouts.dashboard')

@section('title', __('attendance.title'))
@section('page-title', __('attendance.title'))

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4">
        <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ __('attendance.subtitle') }}</p>
        <a href="{{ route('member-attendance.qr') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 {{ $tc->button }} text-white text-sm max-sm:text-xs font-medium rounded-lg transition">
            <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h6v6H3V3zm0 12h6v6H3v-6zm12-12h6v6h-6V3zm0 8h2v2h-2v-2zm4 0h2v6h-6v-2h4v-4zm-6 6h2v2h-2v-2zm4 4h4v-4h-2v2h-2v2z" />
            </svg>
            {{ __('attendance.qr_button') }}
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
        <form action="{{ route('member-attendance.index') }}" method="GET" class="flex flex-row max-sm:flex-col gap-3">
            <div class="flex-1">
                <input
                    type="date"
                    name="date"
                    value="{{ request('date') }}"
                    class="w-full px-3 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring }} transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                >
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-3 py-2 max-sm:py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    {{ __('common.filter') }}
                </button>
                @if(request()->filled('date'))
                    <a href="{{ route('member-attendance.index') }}" class="px-3 py-2 max-sm:py-1.5 text-gray-500 dark:text-gray-400 text-sm font-medium hover:text-gray-700 dark:hover:text-gray-200 transition">
                        {{ __('common.reset') }}
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        @if($attendances->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">{{ __('attendance.member') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">{{ __('attendance.checked_in_at') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">{{ __('attendance.location') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">{{ __('attendance.accuracy') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">{{ __('attendance.status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($attendances as $attendance)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200 font-medium">
                                    {{ $attendance->customer?->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $attendance->checked_in_at?->format('d M Y H:i') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                    @if($attendance->location_name)
                                        {{ $attendance->location_name }}
                                    @elseif($attendance->latitude && $attendance->longitude)
                                        <a href="https://maps.google.com/?q={{ $attendance->latitude }},{{ $attendance->longitude }}" target="_blank" class="{{ $tc->link }}">
                                            {{ $attendance->latitude }}, {{ $attendance->longitude }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $attendance->accuracy ? $attendance->accuracy.' m' : '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300">
                                        {{ __('attendance.status_success') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $attendances->links() }}
            </div>
        @else
            <div class="p-6 text-center text-gray-500 dark:text-gray-400 text-sm">
                {{ __('attendance.no_data') }}
            </div>
        @endif
    </div>
</div>
@endsection
