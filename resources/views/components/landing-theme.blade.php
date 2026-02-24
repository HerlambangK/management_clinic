@php
    $type = business_type() ?? 'clinic';
    $theme = business_theme();

    // Define theme colors for each business type
    $themes = [
        'clinic' => [
            'primary' => 'rose',
            'gradient_from' => 'from-rose-500',
            'gradient_to' => 'to-primary-600',
            'bg_light' => 'bg-rose-100',
            'bg_50' => 'bg-rose-50',
            'text' => 'text-rose-600',
            'text_dark' => 'text-rose-700',
            'border' => 'border-rose-100',
            'border_400' => 'border-rose-400',
            'hover_bg' => 'hover:bg-rose-50',
            'hover_text' => 'hover:text-rose-600',
            'shadow' => 'shadow-rose-200',
            'hover_shadow' => 'hover:shadow-rose-200',
            'group_hover_shadow' => 'group-hover:shadow-rose-300',
        ],
        'salon' => [
            'primary' => 'purple',
            'gradient_from' => 'from-purple-500',
            'gradient_to' => 'to-violet-600',
            'bg_light' => 'bg-purple-100',
            'bg_50' => 'bg-purple-50',
            'text' => 'text-purple-600',
            'text_dark' => 'text-purple-700',
            'border' => 'border-purple-100',
            'border_400' => 'border-purple-400',
            'hover_bg' => 'hover:bg-purple-50',
            'hover_text' => 'hover:text-purple-600',
            'shadow' => 'shadow-purple-200',
            'hover_shadow' => 'hover:shadow-purple-200',
            'group_hover_shadow' => 'group-hover:shadow-purple-300',
        ],
        'barbershop' => [
            'primary' => 'amber',
            'gradient_from' => 'from-amber-500',
            'gradient_to' => 'to-orange-600',
            'bg_light' => 'bg-amber-100',
            'bg_50' => 'bg-amber-50',
            'text' => 'text-amber-600',
            'text_dark' => 'text-amber-700',
            'border' => 'border-amber-100',
            'border_400' => 'border-amber-400',
            'hover_bg' => 'hover:bg-amber-50',
            'hover_text' => 'hover:text-amber-600',
            'shadow' => 'shadow-amber-200',
            'hover_shadow' => 'hover:shadow-amber-200',
            'group_hover_shadow' => 'group-hover:shadow-amber-300',
        ],
        'gym' => [
            'primary' => 'emerald',
            'gradient_from' => 'from-emerald-500',
            'gradient_to' => 'to-teal-600',
            'bg_light' => 'bg-emerald-100',
            'bg_50' => 'bg-emerald-50',
            'text' => 'text-emerald-600',
            'text_dark' => 'text-emerald-700',
            'border' => 'border-emerald-100',
            'border_400' => 'border-emerald-400',
            'hover_bg' => 'hover:bg-emerald-50',
            'hover_text' => 'hover:text-emerald-600',
            'shadow' => 'shadow-emerald-200',
            'hover_shadow' => 'hover:shadow-emerald-200',
            'group_hover_shadow' => 'group-hover:shadow-emerald-300',
        ],
    ];

    $lt = (object) ($themes[$type] ?? $themes['clinic']);
@endphp

{{-- Pass theme to slot --}}
{{ $slot }}
