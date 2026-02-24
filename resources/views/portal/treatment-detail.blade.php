@extends('layouts.portal')

@section('title', __('portal.treatment_detail'))
@section('page-title', __('portal.treatment_detail'))

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Back Button -->
    <a href="{{ route('portal.treatments') }}" class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        {{ __('portal.back_to_treatments') }}
    </a>

    <!-- Treatment Info -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $treatment->appointment?->service?->name ?? '-' }}</h2>
                <p class="mt-1 text-gray-500 dark:text-gray-400">{{ format_date($treatment->created_at) }}</p>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
            @if($treatment->staff)
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('portal.performed_by') }}</p>
                    <p class="mt-1 font-medium text-gray-900 dark:text-white">{{ $treatment->staff->name }}</p>
                </div>
            @endif
            @if($treatment->appointment?->service?->category)
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('portal.category') }}</p>
                    <p class="mt-1 font-medium text-gray-900 dark:text-white">{{ $treatment->appointment->service->category->name }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Treatment Notes -->
    @if($treatment->notes)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('portal.treatment_notes') }}</h3>
            <div class="prose prose-sm dark:prose-invert max-w-none">
                {!! nl2br(e($treatment->notes)) !!}
            </div>
        </div>
    @endif

    <!-- Before/After Photos -->
    @if($treatment->has_photos)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('portal.photos') }}</h3>

            <div class="space-y-6">
                <!-- Before Photos -->
                @if(!empty($treatment->before_photos))
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="w-6 h-6 bg-amber-100 dark:bg-amber-900/50 rounded-full flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('portal.before') }}</p>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($treatment->before_photo_urls as $index => $url)
                                <a href="{{ $url }}" target="_blank" class="block relative group aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700">
                                    <img src="{{ $url }}" alt="Before {{ $index + 1 }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition"></div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- After Photos -->
                @if(!empty($treatment->after_photos))
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="w-6 h-6 bg-green-100 dark:bg-green-900/50 rounded-full flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('portal.after') }}</p>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($treatment->after_photo_urls as $index => $url)
                                <a href="{{ $url }}" target="_blank" class="block relative group aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700">
                                    <img src="{{ $url }}" alt="After {{ $index + 1 }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition"></div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Products Used -->
    @if($treatment->products_used && count($treatment->products_used) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('portal.products_used') }}</h3>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-1">
                @foreach($treatment->products_used as $product)
                    <li>{{ $product }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Recommendations -->
    @if($treatment->recommendations)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('portal.recommendations') }}</h3>
            <div class="prose prose-sm dark:prose-invert max-w-none">
                {!! nl2br(e($treatment->recommendations)) !!}
            </div>
        </div>
    @endif

    <!-- Next Appointment -->
    @if($treatment->follow_up_date)
        <div class="bg-primary-50 dark:bg-primary-900/50 rounded-xl p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-800 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-primary-600 dark:text-primary-300">{{ __('portal.next_appointment_suggested') }}</p>
                    <p class="font-semibold text-primary-700 dark:text-primary-200">{{ format_date($treatment->follow_up_date) }}</p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
