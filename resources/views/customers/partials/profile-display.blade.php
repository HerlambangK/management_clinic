@php
    $businessType = business_type() ?? 'clinic';
    $profileConfig = business_profile_fields();
    $typeField = $profileConfig['type'] ?? null;
    $concernsField = $profileConfig['concerns'] ?? null;
    $locale = app()->getLocale();

    // Get labels based on business type
    $profileLabel = business_config('profile_section') ?? __('customer.profile_section');
    if ($locale === 'en') {
        $profileLabel = business_config('profile_section_en') ?? $profileLabel;
    }

    // Type options for display
    $typeOptions = [];
    if ($typeField) {
        foreach ($typeField['options'] as $key => $labels) {
            $typeOptions[$key] = $labels[$locale] ?? $labels['id'] ?? $key;
        }
    }

    // Concerns options for display
    $concernsOptions = [];
    if ($concernsField) {
        foreach ($concernsField['options'] as $key => $labels) {
            $concernsOptions[$key] = $labels[$locale] ?? $labels['id'] ?? $key;
        }
    }

    // Type label
    $typeLabel = $typeField['label'] ?? __('customer.profile_type');
    if ($locale === 'en' && isset($typeField['label_en'])) {
        $typeLabel = $typeField['label_en'];
    }

    // Concerns label
    $concernsLabel = $concernsField['label'] ?? __('customer.profile_concerns');
    if ($locale === 'en' && isset($concernsField['label_en'])) {
        $concernsLabel = $concernsField['label_en'];
    }

    // No concerns message based on business type
    $noConcernsMessage = __('customer.no_concerns');
    if ($businessType === 'clinic') {
        $noConcernsMessage = __('customer.no_skin_concerns');
    } elseif (in_array($businessType, ['salon', 'barbershop'])) {
        $noConcernsMessage = __('customer.no_hair_concerns');
    } elseif ($businessType === 'gym') {
        $noConcernsMessage = __('customer.no_training_goals');
    }

    // Type badge colors
    $typeColors = [
        // Skin types
        'normal' => 'bg-green-100 text-green-700',
        'oily' => 'bg-yellow-100 text-yellow-700',
        'dry' => 'bg-orange-100 text-orange-700',
        'combination' => 'bg-blue-100 text-blue-700',
        'sensitive' => 'bg-red-100 text-red-700',
        // Hair types
        'damaged' => 'bg-red-100 text-red-700',
        'color_treated' => 'bg-purple-100 text-purple-700',
        'curly' => 'bg-pink-100 text-pink-700',
        'straight' => 'bg-cyan-100 text-cyan-700',
        'wavy' => 'bg-indigo-100 text-indigo-700',
        'thick' => 'bg-emerald-100 text-emerald-700',
        'thin' => 'bg-amber-100 text-amber-700',
        // Gym levels
        'beginner' => 'bg-emerald-100 text-emerald-700',
        'intermediate' => 'bg-blue-100 text-blue-700',
        'advanced' => 'bg-purple-100 text-purple-700',
    ];
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-sm:p-4">
    <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 mb-4 max-sm:mb-3">{{ $profileLabel }}</h3>

    <div class="space-y-4 max-sm:space-y-3">
        <!-- Profile Type (Skin Type / Hair Type) -->
        <div>
            <p class="text-sm max-sm:text-xs text-gray-500 mb-1">{{ $typeLabel }}</p>
            @if($customer->skin_type && isset($typeOptions[$customer->skin_type]))
                <span class="inline-flex items-center px-3 py-1 max-sm:px-2 max-sm:py-0.5 rounded-full text-sm max-sm:text-xs font-medium {{ $typeColors[$customer->skin_type] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ $typeOptions[$customer->skin_type] }}
                </span>
            @else
                <p class="text-gray-400 text-sm max-sm:text-xs">{{ __('customer.not_specified') }}</p>
            @endif
        </div>

        <!-- Profile Concerns (Skin Concerns / Hair Concerns) -->
        <div>
            <p class="text-sm max-sm:text-xs text-gray-500 mb-2">{{ $concernsLabel }}</p>
            @if($customer->skin_concerns && count($customer->skin_concerns) > 0)
                <div class="flex flex-wrap gap-2 max-sm:gap-1">
                    @foreach($customer->skin_concerns as $concern)
                        <span class="inline-flex items-center px-2.5 py-0.5 max-sm:px-2 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                            {{ $concernsOptions[$concern] ?? ucfirst(str_replace('_', ' ', $concern)) }}
                        </span>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-sm max-sm:text-xs">{{ $noConcernsMessage }}</p>
            @endif
        </div>

        <!-- Allergies -->
        <div>
            <p class="text-sm max-sm:text-xs text-gray-500 mb-1">{{ __('customer.allergies') }}</p>
            @if($customer->allergies)
                <p class="text-sm max-sm:text-xs text-gray-900 bg-red-50 text-red-700 px-3 py-2 max-sm:px-2 max-sm:py-1.5 rounded-lg">
                    <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    {{ $customer->allergies }}
                </p>
            @else
                <p class="text-gray-400 text-sm max-sm:text-xs">{{ __('customer.no_allergies') }}</p>
            @endif
        </div>
    </div>
</div>
