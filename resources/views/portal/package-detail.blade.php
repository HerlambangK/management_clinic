@extends('layouts.portal')

@section('title', __('portal.package_detail'))
@section('page-title', __('portal.package_detail'))

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Back Button -->
    <a href="{{ route('portal.packages') }}" class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        {{ __('portal.back_to_packages') }}
    </a>

    <!-- Package Info -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $customerPackage->package?->name }}</h2>
                <p class="mt-1 text-gray-500 dark:text-gray-400">{{ __('portal.purchased_on') }} {{ format_date($customerPackage->created_at) }}</p>
            </div>
            @php
                $statusColors = [
                    'active' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300',
                    'completed' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                    'expired' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                    'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                ];
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$customerPackage->status] ?? 'bg-gray-100 text-gray-700' }}">
                {{ __('portal.package_status_' . $customerPackage->status) }}
            </span>
        </div>

        <!-- Progress -->
        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('portal.session_progress') }}</span>
                <span class="text-sm font-semibold text-gray-900 dark:text-white">
                    {{ $customerPackage->sessions_used }} / {{ $customerPackage->sessions_total }} {{ __('portal.sessions_used') }}
                </span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-3">
                <div class="bg-primary-600 h-3 rounded-full transition-all" style="width: {{ $customerPackage->sessions_total > 0 ? ($customerPackage->sessions_used / $customerPackage->sessions_total) * 100 : 0 }}%"></div>
            </div>
            <p class="mt-2 text-center text-lg font-bold text-primary-600 dark:text-primary-400">
                {{ $customerPackage->sessions_remaining }} {{ __('portal.sessions_remaining') }}
            </p>
        </div>

        <!-- Details -->
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
            @if($customerPackage->expires_at)
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('portal.expires_on') }}</p>
                    <p class="mt-1 font-medium text-gray-900 dark:text-white">{{ format_date($customerPackage->expires_at) }}</p>
                    @if($customerPackage->expires_at->isPast())
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ __('portal.package_expired') }}</p>
                    @elseif(now()->diffInDays($customerPackage->expires_at) <= 30)
                        <p class="mt-1 text-xs text-yellow-600 dark:text-yellow-400">{{ __('portal.expires_soon', ['days' => now()->diffInDays($customerPackage->expires_at)]) }}</p>
                    @endif
                </div>
            @endif
            @if($customerPackage->package?->service)
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('portal.included_services') }}</p>
                    <p class="mt-1 font-medium text-gray-900 dark:text-white">
                        {{ $customerPackage->package->service->name }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Usage History -->
    @if($customerPackage->usages && $customerPackage->usages->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('portal.usage_history') }}</h3>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($customerPackage->usages as $usage)
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ __('portal.session') }} #{{ $loop->iteration }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ format_datetime($usage->used_at) }}
                                </p>
                            </div>
                            @if($usage->treatmentRecord)
                                <a href="{{ route('portal.treatments.show', $usage->treatmentRecord) }}" class="text-sm text-primary-600 hover:text-primary-700">
                                    {{ __('portal.view_treatment') }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Package Description -->
    @if($customerPackage->package?->description)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('portal.package_description') }}</h3>
            <div class="prose prose-sm dark:prose-invert max-w-none">
                {!! nl2br(e($customerPackage->package->description)) !!}
            </div>
        </div>
    @endif
</div>
@endsection
