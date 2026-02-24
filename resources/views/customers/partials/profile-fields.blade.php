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

    // Type options
    $typeOptions = [];
    if ($typeField) {
        foreach ($typeField['options'] as $key => $labels) {
            $typeOptions[$key] = $labels[$locale] ?? $labels['id'] ?? $key;
        }
    }

    // Concerns options
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

    // Current values for edit mode
    $currentType = old('skin_type', $customer->skin_type ?? null);
    $currentConcerns = old('skin_concerns', $customer->skin_concerns ?? []);
@endphp

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
    <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ $profileLabel }}</h3>

    <!-- Profile Type (Skin Type / Hair Type) -->
    @if($typeField && count($typeOptions) > 0)
    <div>
        <label class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ $typeLabel }}</label>
        <div class="flex flex-wrap gap-4 max-sm:gap-2">
            @foreach($typeOptions as $value => $label)
                <label class="inline-flex items-center">
                    <input type="radio" name="skin_type" value="{{ $value }}" class="w-4 h-4 {{ $tc->checkbox ?? 'text-primary-600' }} border-gray-300 dark:border-gray-600 {{ $tc->ring ?? 'focus:ring-primary-500/20 focus:border-primary-400' }} dark:bg-gray-700" {{ $currentType === $value ? 'checked' : '' }}>
                    <span class="ml-2 text-sm max-sm:text-xs text-gray-700 dark:text-gray-300">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        @error('skin_type')
            <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>
    @endif

    <!-- Profile Concerns (Skin Concerns / Hair Concerns) -->
    @if($concernsField && count($concernsOptions) > 0)
    <div class="mt-4 max-sm:mt-3">
        <label class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ $concernsLabel }} ({{ __('customer.select_skin_concerns') }})</label>
        <div class="grid grid-cols-3 max-md:grid-cols-2 max-sm:grid-cols-1 gap-3 max-sm:gap-2">
            @foreach($concernsOptions as $value => $label)
                <label class="inline-flex items-center">
                    <input
                        type="checkbox"
                        name="skin_concerns[]"
                        value="{{ $value }}"
                        class="w-4 h-4 {{ $tc->checkbox ?? 'text-primary-600' }} border-gray-300 dark:border-gray-600 rounded {{ $tc->ring ?? 'focus:ring-primary-500/20 focus:border-primary-400' }} dark:bg-gray-700"
                        {{ in_array($value, $currentConcerns) ? 'checked' : '' }}
                    >
                    <span class="ml-2 text-sm max-sm:text-xs text-gray-700 dark:text-gray-300">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        @error('skin_concerns')
            <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>
    @endif

    <!-- Allergies -->
    <div class="mt-4 max-sm:mt-3">
        <label for="allergies" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('customer.allergies') }}</label>
        <textarea
            id="allergies"
            name="allergies"
            rows="2"
            class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('allergies') border-red-400 @enderror"
            placeholder="{{ __('customer.allergies_placeholder') }}"
        >{{ old('allergies', $customer->allergies ?? '') }}</textarea>
        @error('allergies')
            <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <!-- Notes -->
    <div class="mt-4 max-sm:mt-3">
        <label for="notes" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('customer.notes') }}</label>
        <textarea
            id="notes"
            name="notes"
            rows="3"
            class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('notes') border-red-400 @enderror"
            placeholder="{{ __('customer.notes_placeholder') }}"
        >{{ old('notes', $customer->notes ?? '') }}</textarea>
        @error('notes')
            <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>
</div>
