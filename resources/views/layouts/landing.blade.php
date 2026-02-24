@php
    $businessType = business_type() ?? 'clinic';

    // Theme colors per business type
    $landingThemes = [
        'clinic' => [
            'primary' => 'rose',
            'gradient_from' => 'from-rose-500',
            'gradient_to' => 'to-primary-600',
            'bg_light' => 'bg-rose-100',
            'bg_50' => 'bg-rose-50',
            'text' => 'text-rose-600',
            'text_700' => 'text-rose-700',
            'border' => 'border-rose-100',
            'border_400' => 'border-rose-400',
            'hover_bg' => 'hover:bg-rose-50',
            'hover_text' => 'hover:text-rose-600',
            'shadow' => 'shadow-rose-200',
            'hover_shadow' => 'hover:shadow-rose-200',
            'group_hover_shadow' => 'group-hover:shadow-rose-300',
            'bg_gradient_1' => 'from-rose-200/40 to-primary-200/30',
            'bg_gradient_2' => 'from-rose-100/50 to-peach/50',
            'bg_gradient_3' => 'from-primary-100/30 to-rose-100/30',
        ],
        'salon' => [
            'primary' => 'purple',
            'gradient_from' => 'from-purple-500',
            'gradient_to' => 'to-violet-600',
            'bg_light' => 'bg-purple-100',
            'bg_50' => 'bg-purple-50',
            'text' => 'text-purple-600',
            'text_700' => 'text-purple-700',
            'border' => 'border-purple-100',
            'border_400' => 'border-purple-400',
            'hover_bg' => 'hover:bg-purple-50',
            'hover_text' => 'hover:text-purple-600',
            'shadow' => 'shadow-purple-200',
            'hover_shadow' => 'hover:shadow-purple-200',
            'group_hover_shadow' => 'group-hover:shadow-purple-300',
            'bg_gradient_1' => 'from-purple-200/40 to-violet-200/30',
            'bg_gradient_2' => 'from-purple-100/50 to-violet-50/50',
            'bg_gradient_3' => 'from-violet-100/30 to-purple-100/30',
        ],
        'barbershop' => [
            'primary' => 'blue',
            'gradient_from' => 'from-blue-500',
            'gradient_to' => 'to-blue-600',
            'bg_light' => 'bg-blue-100',
            'bg_50' => 'bg-blue-50',
            'text' => 'text-blue-600',
            'text_700' => 'text-blue-700',
            'border' => 'border-blue-200',
            'border_400' => 'border-blue-500',
            'hover_bg' => 'hover:bg-blue-50',
            'hover_text' => 'hover:text-blue-600',
            'shadow' => 'shadow-blue-200',
            'hover_shadow' => 'hover:shadow-blue-200',
            'group_hover_shadow' => 'group-hover:shadow-blue-300',
            'bg_gradient_1' => 'from-blue-200/30 to-sky-200/25',
            'bg_gradient_2' => 'from-blue-100/40 to-sky-100/35',
            'bg_gradient_3' => 'from-sky-100/25 to-blue-100/25',
        ],
        'gym' => [
            'primary' => 'emerald',
            'gradient_from' => 'from-emerald-500',
            'gradient_to' => 'to-teal-600',
            'bg_light' => 'bg-emerald-100',
            'bg_50' => 'bg-emerald-50',
            'text' => 'text-emerald-600',
            'text_700' => 'text-emerald-700',
            'border' => 'border-emerald-200',
            'border_400' => 'border-emerald-400',
            'hover_bg' => 'hover:bg-emerald-50',
            'hover_text' => 'hover:text-emerald-600',
            'shadow' => 'shadow-emerald-200',
            'hover_shadow' => 'hover:shadow-emerald-200',
            'group_hover_shadow' => 'group-hover:shadow-emerald-300',
            'bg_gradient_1' => 'from-emerald-200/40 to-teal-200/30',
            'bg_gradient_2' => 'from-emerald-100/50 to-teal-50/50',
            'bg_gradient_3' => 'from-teal-100/30 to-emerald-100/30',
        ],
    ];

    $lt = (object) ($landingThemes[$businessType] ?? $landingThemes['clinic']);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', brand_name() . ' - ' . brand_tagline())</title>
    <meta name="description" content="@yield('description', brand_description())">
    <meta name="author" content="Bembie">
    <meta name="application-name" content="{{ brand_name() }}">
    <meta name="theme-color" content="{{ brand_color('primary') }}">
    <meta property="og:title" content="@yield('title', brand_name() . ' - ' . brand_tagline())">
    <meta property="og:description" content="@yield('description', brand_description())">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', brand_name() . ' - ' . brand_tagline())">
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
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">

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
    @if(brand_custom_css())
        <style>{!! brand_custom_css() !!}</style>
    @endif
    @if(brand_custom_script('head'))
        {!! brand_custom_script('head') !!}
    @endif
</head>
<body class="bg-cream antialiased" x-data="{ mobileMenu: false }">

    <!-- Decorative Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-gradient-to-br {{ $lt->bg_gradient_1 }} blob-1 blur-3xl animate-pulse-soft"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-gradient-to-tr {{ $lt->bg_gradient_2 }} blob-2 blur-3xl" style="animation-delay: 1.5s;"></div>
        <div class="absolute top-1/2 left-1/2 w-[400px] h-[400px] bg-gradient-to-r {{ $lt->bg_gradient_3 }} rounded-full blur-3xl transform -translate-x-1/2 -translate-y-1/2"></div>
    </div>

    @yield('content')

    @stack('scripts')
    @if(brand_custom_script('body'))
        {!! brand_custom_script('body') !!}
    @endif
</body>
</html>
