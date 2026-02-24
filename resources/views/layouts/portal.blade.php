<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('portal.my_account')) - {{ brand_name() }}</title>
    <meta name="description" content="@yield('description', brand_description())">
    <meta name="author" content="Bembie">
    <meta name="application-name" content="{{ brand_name() }}">
    <meta name="theme-color" content="{{ brand_color('primary') }}">
    <meta property="og:title" content="@yield('title', __('portal.my_account')) - {{ brand_name() }}">
    <meta property="og:description" content="@yield('description', brand_description())">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', __('portal.my_account')) - {{ brand_name() }}">
    <meta name="twitter:description" content="@yield('description', brand_description())">
    @if(brand_logo('favicon'))
        <link rel="icon" type="image/x-icon" href="{{ brand_logo('favicon') }}">
    @endif
    @if(brand_logo('logo'))
        <meta property="og:image" content="{{ brand_logo('logo') }}">
        <meta name="twitter:image" content="{{ brand_logo('logo') }}">
    @endif

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $palette = brand_palette();
    @endphp
    <style>
        :root {
            --brand-primary: {{ brand_color('primary') }};
            --brand-primary-hover: {{ brand_color('primary_hover') }};
            --brand-primary-light: {{ brand_color('primary_light') }};
@if($palette)
            --color-primary-50: {{ $palette['colors']['50'] }};
            --color-primary-100: {{ $palette['colors']['100'] }};
            --color-primary-200: {{ $palette['colors']['200'] }};
            --color-primary-300: {{ $palette['colors']['300'] }};
            --color-primary-400: {{ $palette['colors']['400'] }};
            --color-primary-500: {{ $palette['colors']['500'] }};
            --color-primary-600: {{ $palette['colors']['600'] }};
            --color-primary-700: {{ $palette['colors']['700'] }};
            --color-primary-800: {{ $palette['colors']['800'] }};
            --color-primary-900: {{ $palette['colors']['900'] }};
@endif
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 antialiased min-h-screen">
    @auth('customer')
        @php
            $portalPlan = customer_portal_plan();
            $isPremiumPortal = $portalPlan === 'premium';
            $premiumBadgeLabel = __('portal.premium_badge');
        @endphp
        <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">
            <!-- Mobile sidebar backdrop -->
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/50 z-40 lg:hidden" @click="sidebarOpen = false"></div>

            <!-- Sidebar -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-xl transform transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
                <!-- Logo -->
                <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 dark:border-gray-700">
                    <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-3">
                        @if(brand_logo('logo'))
                            <img src="{{ brand_logo('logo') }}" alt="{{ brand_name() }}" class="h-8">
                        @else
                            <span class="text-xl font-semibold text-primary-600">{{ brand_name() }}</span>
                        @endif
                    </a>
                    <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="px-4 py-6 space-y-1">
                    <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('portal.dashboard') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/50 dark:text-primary-300' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        {{ __('portal.dashboard') }}
                    </a>

                    <a href="{{ route('portal.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('portal.profile*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/50 dark:text-primary-300' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        {{ __('portal.my_profile') }}
                    </a>

                    @if(has_feature('online_booking'))
                        @if(customer_portal_feature_locked('online_booking'))
                            <div class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 cursor-not-allowed relative overflow-hidden">
                                <div class="flex items-center gap-3 blur-sm opacity-70">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ __('portal.my_appointments') }}</span>
                                </div>
                                <span class="ml-auto inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wide text-amber-800 bg-amber-100 px-2 py-0.5 rounded-full">
                                    <svg class="w-3 h-3 text-amber-500 animate-pulse" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.159c.969 0 1.371 1.24.588 1.81l-3.366 2.445a1 1 0 00-.364 1.118l1.287 3.955c.3.921-.755 1.688-1.538 1.118l-3.366-2.445a1 1 0 00-1.176 0l-3.366 2.445c-.783.57-1.838-.197-1.538-1.118l1.287-3.955a1 1 0 00-.364-1.118L2.025 9.382c-.783-.57-.38-1.81.588-1.81h4.159a1 1 0 00.95-.69l1.286-3.955z" />
                                    </svg>
                                    <span>{{ $premiumBadgeLabel }}</span>
                                </span>
                            </div>
                        @else
                            <a href="{{ route('portal.appointments') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('portal.appointments*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/50 dark:text-primary-300' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ __('portal.my_appointments') }}
                            </a>
                        @endif
                    @endif

                    @if(has_feature('treatment_records'))
                        @if(customer_portal_feature_locked('treatment_records'))
                            <div class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 cursor-not-allowed relative overflow-hidden">
                                <div class="flex items-center gap-3 blur-sm opacity-70">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span>{{ __('portal.treatment_history') }}</span>
                                </div>
                                <span class="ml-auto inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wide text-amber-800 bg-amber-100 px-2 py-0.5 rounded-full">
                                    <svg class="w-3 h-3 text-amber-500 animate-pulse" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.159c.969 0 1.371 1.24.588 1.81l-3.366 2.445a1 1 0 00-.364 1.118l1.287 3.955c.3.921-.755 1.688-1.538 1.118l-3.366-2.445a1 1 0 00-1.176 0l-3.366 2.445c-.783.57-1.838-.197-1.538-1.118l1.287-3.955a1 1 0 00-.364-1.118L2.025 9.382c-.783-.57-.38-1.81.588-1.81h4.159a1 1 0 00.95-.69l1.286-3.955z" />
                                    </svg>
                                    <span>{{ $premiumBadgeLabel }}</span>
                                </span>
                            </div>
                        @else
                            <a href="{{ route('portal.treatments') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('portal.treatments*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/50 dark:text-primary-300' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                {{ __('portal.treatment_history') }}
                            </a>
                        @endif
                    @endif

                    @if(has_feature('packages'))
                        @if(customer_portal_feature_locked('packages'))
                            <div class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 cursor-not-allowed relative overflow-hidden">
                                <div class="flex items-center gap-3 blur-sm opacity-70">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    <span>{{ __('portal.my_packages') }}</span>
                                </div>
                                <span class="ml-auto inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wide text-amber-800 bg-amber-100 px-2 py-0.5 rounded-full">
                                    <svg class="w-3 h-3 text-amber-500 animate-pulse" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.159c.969 0 1.371 1.24.588 1.81l-3.366 2.445a1 1 0 00-.364 1.118l1.287 3.955c.3.921-.755 1.688-1.538 1.118l-3.366-2.445a1 1 0 00-1.176 0l-3.366 2.445c-.783.57-1.838-.197-1.538-1.118l1.287-3.955a1 1 0 00-.364-1.118L2.025 9.382c-.783-.57-.38-1.81.588-1.81h4.159a1 1 0 00.95-.69l1.286-3.955z" />
                                    </svg>
                                    <span>{{ $premiumBadgeLabel }}</span>
                                </span>
                            </div>
                        @else
                            <a href="{{ route('portal.packages') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('portal.packages*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/50 dark:text-primary-300' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                {{ __('portal.my_packages') }}
                            </a>
                        @endif
                    @endif

                    @if(has_feature('loyalty'))
                        @if(customer_portal_feature_locked('loyalty'))
                            <div class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 cursor-not-allowed relative overflow-hidden">
                                <div class="flex items-center gap-3 blur-sm opacity-70">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ __('portal.loyalty_points') }}</span>
                                </div>
                                <span class="ml-auto inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wide text-amber-800 bg-amber-100 px-2 py-0.5 rounded-full">
                                    <svg class="w-3 h-3 text-amber-500 animate-pulse" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.159c.969 0 1.371 1.24.588 1.81l-3.366 2.445a1 1 0 00-.364 1.118l1.287 3.955c.3.921-.755 1.688-1.538 1.118l-3.366-2.445a1 1 0 00-1.176 0l-3.366 2.445c-.783.57-1.838-.197-1.538-1.118l1.287-3.955a1 1 0 00-.364-1.118L2.025 9.382c-.783-.57-.38-1.81.588-1.81h4.159a1 1 0 00.95-.69l1.286-3.955z" />
                                    </svg>
                                    <span>{{ $premiumBadgeLabel }}</span>
                                </span>
                            </div>
                        @else
                            <a href="{{ route('portal.loyalty') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('portal.loyalty*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/50 dark:text-primary-300' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('portal.loyalty_points') }}
                            </a>
                        @endif
                    @endif

                    @if(customer_portal_feature_locked('transactions'))
                        <div class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 cursor-not-allowed relative overflow-hidden">
                            <div class="flex items-center gap-3 blur-sm opacity-70">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                <span>{{ __('portal.transactions') }}</span>
                            </div>
                            <span class="ml-auto inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wide text-amber-800 bg-amber-100 px-2 py-0.5 rounded-full">
                                <svg class="w-3 h-3 text-amber-500 animate-pulse" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.159c.969 0 1.371 1.24.588 1.81l-3.366 2.445a1 1 0 00-.364 1.118l1.287 3.955c.3.921-.755 1.688-1.538 1.118l-3.366-2.445a1 1 0 00-1.176 0l-3.366 2.445c-.783.57-1.838-.197-1.538-1.118l1.287-3.955a1 1 0 00-.364-1.118L2.025 9.382c-.783-.57-.38-1.81.588-1.81h4.159a1 1 0 00.95-.69l1.286-3.955z" />
                                </svg>
                                <span>{{ $premiumBadgeLabel }}</span>
                            </span>
                        </div>
                    @else
                        <a href="{{ route('portal.transactions') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('portal.transactions*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/50 dark:text-primary-300' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            {{ __('portal.transactions') }}
                        </a>
                    @endif

                    @if(has_feature('attendance'))
                        <a href="{{ route('portal.attendance.scan') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('portal.attendance*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/50 dark:text-primary-300' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h6v6H3V3zm0 12h6v6H3v-6zm12-12h6v6h-6V3zm0 8h2v2h-2v-2zm4 0h2v6h-6v-2h4v-4zm-6 6h2v2h-2v-2zm4 4h4v-4h-2v2h-2v2z"></path>
                            </svg>
                            {{ __('attendance.scan_title') }}
                        </a>
                    @endif
                </nav>

                <!-- Book Now Button -->
                @if(has_feature('online_booking'))
                    <div class="px-4 mt-4">
                        @if(customer_portal_feature_locked('online_booking'))
                            <div class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 rounded-xl font-medium cursor-not-allowed relative overflow-hidden">
                                <div class="flex items-center gap-2 blur-sm opacity-70">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span>{{ __('portal.book_appointment') }}</span>
                                </div>
                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold uppercase tracking-wide text-amber-800 bg-amber-100 px-2 py-0.5 rounded-full">
                                    <svg class="w-3 h-3 text-amber-500 animate-pulse" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.159c.969 0 1.371 1.24.588 1.81l-3.366 2.445a1 1 0 00-.364 1.118l1.287 3.955c.3.921-.755 1.688-1.538 1.118l-3.366-2.445a1 1 0 00-1.176 0l-3.366 2.445c-.783.57-1.838-.197-1.538-1.118l1.287-3.955a1 1 0 00-.364-1.118L2.025 9.382c-.783-.57-.38-1.81.588-1.81h4.159a1 1 0 00.95-.69l1.286-3.955z" />
                                    </svg>
                                    <span>{{ $premiumBadgeLabel }}</span>
                                </span>
                            </div>
                        @else
                            <a href="{{ route('booking.index') }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                {{ __('portal.book_appointment') }}
                            </a>
                        @endif
                    </div>
                @endif
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Top Header -->
                <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between h-16 px-4 lg:px-8">
                        <!-- Left Side: Menu button + Page Title -->
                        <div class="flex items-center gap-3">
                            <!-- Mobile menu button -->
                            <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>

                            <!-- Page Title -->
                            <h1 class="text-base lg:text-lg font-semibold text-gray-900 dark:text-white">
                                @yield('page-title', __('portal.dashboard'))
                            </h1>
                        </div>

                        <!-- Right Side -->
                        <div class="flex items-center gap-4">
                            <!-- Dark Mode Toggle -->
                            <button @click="darkMode = !darkMode" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                                <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </button>

                            <!-- User Menu -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-primary-700 dark:text-primary-300">{{ substr(auth('customer')->user()->name, 0, 1) }}</span>
                                    </div>
                                    <span class="hidden sm:block text-sm font-medium text-gray-700 dark:text-gray-300">{{ auth('customer')->user()->name }}</span>
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                                    <a href="{{ route('portal.profile') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ __('portal.my_profile') }}
                                    </a>
                                    <hr class="my-1 border-gray-200 dark:border-gray-700">
                                    <form action="{{ route('portal.logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            {{ __('portal.logout') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-4 lg:p-8">
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-800 rounded-xl">
                            <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-xl">
                            <p class="text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    @else
        @yield('content')
    @endauth

    @stack('scripts')
</body>
</html>
