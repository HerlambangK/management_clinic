@extends('layouts.portal')

@section('title', __('portal.treatment_history'))
@section('page-title', __('portal.treatment_history'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <p class="text-gray-500 dark:text-gray-400">{{ __('portal.treatments_subtitle') }}</p>
    </div>

    <!-- Treatments List -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        @if($treatments->count() > 0)
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($treatments as $treatment)
                    <a href="{{ route('portal.treatments.show', $treatment) }}" class="block p-4 max-sm:p-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-center gap-3 max-sm:gap-2">
                            <div class="w-12 h-12 max-sm:w-10 max-sm:h-10 bg-purple-100 dark:bg-purple-900/50 rounded-xl flex flex-col items-center justify-center flex-shrink-0">
                                <span class="text-xs max-sm:text-[10px] font-medium text-purple-600 dark:text-purple-300">{{ $treatment->created_at->format('M') }}</span>
                                <span class="text-base max-sm:text-sm font-bold text-purple-700 dark:text-purple-200">{{ $treatment->created_at->format('d') }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm max-sm:text-xs font-medium text-gray-900 dark:text-white truncate">
                                    {{ $treatment->appointment?->service?->name ?? '-' }}
                                </p>
                                <div class="mt-1 flex items-center gap-3 max-sm:gap-2 text-xs text-gray-500 dark:text-gray-400">
                                    @if($treatment->staff)
                                        <span class="flex items-center gap-1 max-sm:hidden">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            {{ $treatment->staff->name }}
                                        </span>
                                    @endif
                                    <span>{{ format_date($treatment->created_at) }}</span>
                                </div>
                            </div>
                            <svg class="w-5 h-5 max-sm:w-4 max-sm:h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                {{ $treatments->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">{{ __('portal.no_treatments') }}</h3>
                <p class="mt-2 text-gray-500 dark:text-gray-400">{{ __('portal.no_treatments_desc') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
