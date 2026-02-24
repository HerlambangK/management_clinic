@extends('layouts.dashboard')

@section('title', __('treatment.title'))
@section('page-title', __('treatment.title'))

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Header -->
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4 max-sm:gap-3">
        <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ __('treatment.subtitle') }}</p>
        <a href="{{ route('treatment-records.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm max-sm:text-xs font-medium rounded-lg transition">
            <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('treatment.add') }}
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 max-sm:px-3 max-sm:py-2 rounded-lg text-sm max-sm:text-xs">
            {{ session('success') }}
        </div>
    @endif

    <!-- Records List -->
    @if($records->count() > 0)
        <!-- Desktop Table -->
        <div class="hidden sm:block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.date') }}</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('treatment.customer') }}</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('treatment.service') }}</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('treatment.beautician') }}</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('treatment.follow_up') }}</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($records as $record)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ format_date($record->appointment->appointment_date) }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $record->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <a href="{{ route('customers.show', $record->customer) }}" class="text-sm font-medium text-gray-900 dark:text-gray-100 {{ $tc->link_hover ?? 'hover:text-rose-600' }} dark:hover:text-rose-400">
                                        {{ $record->customer->name }}
                                    </a>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $record->customer->phone }}</div>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $record->appointment->service->name }}</div>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $record->staff->name }}</div>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    @if($record->follow_up_date)
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ format_date($record->follow_up_date) }}</div>
                                        @if($record->follow_up_date->isPast())
                                            <span class="text-xs text-red-500 dark:text-red-400">{{ __('treatment.overdue') }}</span>
                                        @elseif($record->follow_up_date->isToday())
                                            <span class="text-xs text-yellow-500 dark:text-yellow-400">{{ __('treatment.today') }}</span>
                                        @else
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $record->follow_up_date->diffForHumans() }}</span>
                                        @endif
                                    @else
                                        <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('treatment-records.show', $record) }}" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300" title="{{ __('treatment.view_detail') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('treatment-records.edit', $record) }}" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300" title="{{ __('common.edit') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="sm:hidden bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($records as $record)
                    <a href="{{ route('treatment-records.show', $record) }}" class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 active:bg-gray-100 dark:active:bg-gray-700">
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $record->customer->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $record->appointment->service->name }}</p>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 flex-shrink-0">{{ format_date($record->appointment->appointment_date) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500 dark:text-gray-400">{{ __('treatment.by') }}: {{ $record->staff->name }}</span>
                            @if($record->follow_up_date)
                                @if($record->follow_up_date->isPast())
                                    <span class="text-red-500 dark:text-red-400">{{ __('treatment.follow_up_overdue') }}</span>
                                @elseif($record->follow_up_date->isToday())
                                    <span class="text-yellow-500 dark:text-yellow-400">{{ __('treatment.follow_up_today') }}</span>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">{{ __('treatment.follow_up') }} {{ format_date($record->follow_up_date, 'd M') }}</span>
                                @endif
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        @if($records->hasPages())
            <div class="mt-4">
                {{ $records->links() }}
            </div>
        @endif
    @else
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-10 max-sm:p-6 text-center">
            <div class="w-14 h-14 max-sm:w-12 max-sm:h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 max-sm:w-6 max-sm:h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-base max-sm:text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">{{ __('treatment.no_records') }}</h3>
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 mb-4">{{ __('treatment.no_records_desc') }}</p>
            <a href="{{ route('treatment-records.create') }}" class="inline-flex items-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm max-sm:text-xs font-medium rounded-lg transition">
                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('treatment.add_first') }}
            </a>
        </div>
    @endif
</div>
@endsection
