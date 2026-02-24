@extends('layouts.portal')

@section('title', __('portal.my_packages'))
@section('page-title', __('portal.my_packages'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <p class="text-gray-500 dark:text-gray-400">{{ __('portal.packages_subtitle') }}</p>
    </div>

    <!-- Packages List -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        @if($packages->count() > 0)
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($packages as $customerPackage)
                    <a href="{{ route('portal.packages.show', $customerPackage) }}" class="block p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-purple-100 dark:bg-purple-900/50 rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="text-base font-medium text-gray-900 dark:text-white truncate">
                                        {{ $customerPackage->package?->name }}
                                    </p>
                                    @php
                                        $statusColors = [
                                            'active' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300',
                                            'completed' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                            'expired' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                                            'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$customerPackage->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ __('portal.package_status_' . $customerPackage->status) }}
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-500 dark:text-gray-400">{{ __('portal.remaining_sessions') }}</span>
                                        <span class="font-semibold text-primary-600">{{ $customerPackage->sessions_remaining }} / {{ $customerPackage->sessions_total }}</span>
                                    </div>
                                    <div class="mt-1 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $customerPackage->sessions_total > 0 ? ($customerPackage->sessions_remaining / $customerPackage->sessions_total) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                                @if($customerPackage->expires_at)
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        {{ __('portal.expires') }}: {{ format_date($customerPackage->expires_at) }}
                                    </p>
                                @endif
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                {{ $packages->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">{{ __('portal.no_packages') }}</h3>
                <p class="mt-2 text-gray-500 dark:text-gray-400">{{ __('portal.no_packages_desc') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
