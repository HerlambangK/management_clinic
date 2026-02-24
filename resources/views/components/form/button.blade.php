@props([
    'type' => 'submit',
    'variant' => 'primary', // primary, secondary, danger
    'size' => 'md', // sm, md, lg
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 font-medium rounded-lg transition';

    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-xs',
        'lg' => 'px-6 py-3 text-base',
        default => 'px-4 py-2.5 max-sm:py-2 text-sm max-sm:text-xs',
    };

    // Use $tc from BusinessServiceProvider for theme consistency
    $variantClasses = match($variant) {
        'secondary' => 'bg-gray-100 text-gray-700 hover:bg-gray-200',
        'danger' => 'bg-red-500 text-white hover:bg-red-600',
        'outline' => ($tc->buttonOutline ?? 'border border-rose-500 text-rose-500 hover:bg-rose-50'),
        default => ($tc->button ?? 'bg-rose-500 hover:bg-rose-600') . ' text-white',
    };
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => "{$baseClasses} {$sizeClasses} {$variantClasses}"]) }}
>
    {{ $slot }}
</button>
