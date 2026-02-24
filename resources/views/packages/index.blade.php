@extends('layouts.dashboard')

@section('title', __('package.title'))
@section('page-title', __('package.title'))

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Header -->
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4 max-sm:gap-3">
        <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">{{ __('package.subtitle') }}</p>
        <a href="{{ route('packages.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 {{ $tc->button }} text-white text-sm max-sm:text-xs font-medium rounded-lg transition">
            <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('package.add') }}
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 max-sm:p-3">
        <form action="{{ route('packages.index') }}" method="GET" class="flex flex-row max-sm:flex-col gap-3">
            <div class="flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="{{ __('package.search_placeholder') }}"
                    class="w-full px-3 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring }} transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"
                >
            </div>
            <div class="flex gap-2">
                <div class="relative">
                    <select name="status" class="w-full pl-3 pr-8 py-2 max-sm:py-1.5 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 {{ $tc->ring }} transition appearance-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">{{ __('package.all_status') }}</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('common.active') }}</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('common.inactive') }}</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                <button type="submit" class="px-3 py-2 max-sm:py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    {{ __('common.filter') }}
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('packages.index') }}" class="px-3 py-2 max-sm:py-1.5 text-gray-500 dark:text-gray-400 text-sm font-medium hover:text-gray-700 dark:hover:text-gray-200 transition">
                        {{ __('common.reset') }}
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 max-sm:px-3 max-sm:py-2 rounded-lg text-sm max-sm:text-xs">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 max-sm:px-3 max-sm:py-2 rounded-lg text-sm max-sm:text-xs">
            {{ session('error') }}
        </div>
    @endif

    <!-- Packages Grid -->
    @if($packages->count() > 0)
        <div class="grid grid-cols-3 max-lg:grid-cols-2 max-sm:grid-cols-1 gap-4 max-sm:gap-3">
            @foreach($packages as $package)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition {{ !$package->is_active ? 'opacity-60' : '' }}">
                    <div class="p-4 max-sm:p-3">
                        <div class="flex items-start justify-between mb-2">
                            <div class="min-w-0 flex-1">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-sm max-sm:text-sm truncate">{{ $package->name }}</h3>
                                @if($package->service)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $package->service->name }}</p>
                                @endif
                            </div>
                            @if($package->discount_percentage > 0)
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400 flex-shrink-0 ml-2">
                                    -{{ $package->discount_percentage }}%
                                </span>
                            @endif
                        </div>

                        <div class="space-y-1.5 mb-3 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 dark:text-gray-400">{{ __('package.total_sessions') }}</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $package->total_sessions }}x</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 dark:text-gray-400">{{ __('package.validity_days') }}</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $package->validity_days }} {{ __('common.days') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 dark:text-gray-400">{{ __('package.sold') }}</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $package->customer_packages_count ?? 0 }}</span>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 dark:border-gray-700 pt-3">
                            <div class="flex items-baseline gap-2 mb-2">
                                <span class="text-lg font-bold {{ $tc->linkDark }}">{{ $package->formatted_package_price }}</span>
                                @if($package->discount_percentage > 0)
                                    <span class="text-xs text-gray-400 dark:text-gray-500 line-through">{{ $package->formatted_original_price }}</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('packages.show', $package) }}" class="flex-1 text-center px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                    {{ __('common.detail') }}
                                </a>
                                <a href="{{ route('packages.edit', $package) }}" class="px-2 py-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('packages.toggle-active', $package) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-2 py-1.5 {{ $package->is_active ? 'text-green-500 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300' }} transition" title="{{ $package->is_active ? __('package.deactivate') : __('package.activate') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $package->is_active ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' }}" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($packages->hasPages())
            <div class="mt-4">
                {{ $packages->links() }}
            </div>
        @endif
    @else
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-10 max-sm:p-6 text-center">
            <div class="w-14 h-14 max-sm:w-12 max-sm:h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 max-sm:w-6 max-sm:h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <h3 class="text-base max-sm:text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">{{ __('package.no_packages') }}</h3>
            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 mb-4">{{ __('package.create_package_description') }}</p>
            <a href="{{ route('packages.create') }}" class="inline-flex items-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 {{ $tc->button }} text-white text-sm max-sm:text-xs font-medium rounded-lg transition">
                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('package.add_first_package') }}
            </a>
        </div>
    @endif
</div>
@endsection
