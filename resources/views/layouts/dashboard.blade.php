<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <!-- Critical: Set dark background BEFORE anything else renders -->
    <script>
        (function() {
            if (localStorage.getItem('darkMode') === 'true') {
                document.documentElement.classList.add('dark');
                document.documentElement.style.backgroundColor = '#111827';
                document.documentElement.style.colorScheme = 'dark';
            }
        })();
    </script>
    <style>
        /* Immediate dark mode background - prevents white flash */
        html.dark, html.dark body { background-color: #111827 !important; }
        /* Hide body until styles loaded to prevent flash */
        body { opacity: 0; }
        body.loaded { opacity: 1; transition: opacity 0.1s ease-in; }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ brand_name() }}</title>
    <meta name="description" content="@yield('description', brand_description())">
    <meta name="author" content="Bembie">
    <meta name="application-name" content="{{ brand_name() }}">
    <meta property="og:title" content="@yield('title', 'Dashboard') - {{ brand_name() }}">
    <meta property="og:description" content="@yield('description', brand_description())">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Dashboard') - {{ brand_name() }}">
    <meta name="twitter:description" content="@yield('description', brand_description())">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="{{ brand_color('primary') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ brand_name() }}">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/images/icons/icon-192x192.png">

    @if(brand_logo('favicon'))
        <link rel="icon" type="image/x-icon" href="{{ brand_logo('favicon') }}">
    @endif
    @if(brand_logo('logo'))
        <meta property="og:image" content="{{ brand_logo('logo') }}">
        <meta name="twitter:image" content="{{ brand_logo('logo') }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $palette = brand_palette();
    @endphp
    <style>
        /* Hide native dropdown arrow - fallback for older CSS builds */
        select.appearance-none {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
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
    @if(brand_custom_css())
        <style>{!! brand_custom_css() !!}</style>
    @endif
    @if(brand_custom_script('head'))
        {!! brand_custom_script('head') !!}
    @endif
    @stack('styles')
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-900 dark:text-gray-100" x-data="{ sidebarOpen: false }" onload="this.classList.add('loaded')">
<script>document.body.classList.add('loaded');</script>
    <div class="flex min-h-screen">
        <!-- Sidebar Overlay (Mobile) -->
        <div
            x-show="sidebarOpen"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/50 z-40 hidden max-lg:block"
            @click="sidebarOpen = false"
        ></div>

        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Header -->
            @include('components.header')

            <!-- Page Content -->
            <main class="p-6 max-md:p-4">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
    @if(brand_custom_script('body'))
        {!! brand_custom_script('body') !!}
    @endif

    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('ServiceWorker registered:', registration.scope);
                    })
                    .catch(function(error) {
                        console.log('ServiceWorker registration failed:', error);
                    });
            });
        }
    </script>
</body>
</html>
