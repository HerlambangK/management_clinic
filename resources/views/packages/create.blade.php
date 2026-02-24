@extends('layouts.dashboard')

@section('title', __('package.create'))
@section('page-title', __('package.create'))

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('packages.index') }}" class="inline-flex items-center text-sm max-sm:text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 mb-6 max-sm:mb-4">
        <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        {{ __('common.back') }}
    </a>

    <form action="{{ route('packages.store') }}" method="POST" class="space-y-6 max-sm:space-y-4">
        @csrf

        <!-- Basic Info -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-white mb-4 max-sm:mb-3">{{ __('package.package_info') }}</h3>

            <div class="space-y-4 max-sm:space-y-3">
                <div>
                    <label for="name" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('package.name') }} <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        class="w-full px-4 py-2.5 max-sm:py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition @error('name') border-red-400 @enderror"
                        placeholder="{{ __('package.example_name') }}"
                        required
                    >
                    @error('name')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="service_id" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('package.related_service') }}</label>
                    <div class="relative">
                        <select
                            id="service_id"
                            name="service_id"
                            class="w-full pl-4 pr-12 py-2.5 max-sm:py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition appearance-none @error('service_id') border-red-400 @enderror"
                        >
                            <option value="">{{ __('package.select_service_optional') }}</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }} - {{ format_currency($service->price) }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    @error('service_id')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('common.description') }}</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        class="w-full px-4 py-2.5 max-sm:py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition @error('description') border-red-400 @enderror"
                        placeholder="{{ __('package.description_placeholder') }}"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Sessions & Validity -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-white mb-4 max-sm:mb-3">{{ __('package.sessions_validity') }}</h3>

            <div class="grid sm:grid-cols-2 gap-4 max-sm:gap-3">
                <div>
                    <label for="total_sessions" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('package.total_sessions') }} <span class="text-red-500">*</span></label>
                    <input
                        type="number"
                        id="total_sessions"
                        name="total_sessions"
                        value="{{ old('total_sessions', 10) }}"
                        min="1"
                        max="100"
                        class="w-full px-4 py-2.5 max-sm:py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition @error('total_sessions') border-red-400 @enderror"
                        required
                    >
                    @error('total_sessions')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="validity_days" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('package.validity_days_label') }} <span class="text-red-500">*</span></label>
                    <input
                        type="number"
                        id="validity_days"
                        name="validity_days"
                        value="{{ old('validity_days', 365) }}"
                        min="1"
                        max="3650"
                        class="w-full px-4 py-2.5 max-sm:py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition @error('validity_days') border-red-400 @enderror"
                        required
                    >
                    @error('validity_days')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Pricing -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4" x-data="packagePricing({{ old('original_price', 0) }}, {{ old('package_price', 0) }})">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-white mb-4 max-sm:mb-3">{{ __('package.pricing') }}</h3>

            <div class="grid sm:grid-cols-2 gap-4 max-sm:gap-3">
                <div>
                    <label for="original_price_display" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('package.original_price') }} <span class="text-red-500">*</span></label>
                    <div class="relative flex">
                        <span class="inline-flex items-center px-3.5 text-sm font-medium text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 border border-r-0 border-gray-200 dark:border-gray-600 rounded-l-lg select-none">Rp</span>
                        <input
                            type="text"
                            id="original_price_display"
                            x-model="originalPriceDisplay"
                            @input="updateOriginalPrice($event)"
                            @keydown="allowOnlyNumbers($event)"
                            inputmode="numeric"
                            autocomplete="off"
                            class="w-full px-4 py-2.5 max-sm:py-2 text-sm text-right bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 dark:focus:ring-rose-500/30 dark:focus:border-rose-400 transition @error('original_price') border-red-400 dark:border-red-400 @enderror"
                            placeholder="0"
                            required
                        >
                        <input type="hidden" name="original_price" :value="originalPrice">
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('package.normal_price_help') }}</p>
                    @error('original_price')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="package_price_display" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('package.package_price') }} <span class="text-red-500">*</span></label>
                    <div class="relative flex">
                        <span class="inline-flex items-center px-3.5 text-sm font-medium text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 border border-r-0 border-gray-200 dark:border-gray-600 rounded-l-lg select-none">Rp</span>
                        <input
                            type="text"
                            id="package_price_display"
                            x-model="packagePriceDisplay"
                            @input="updatePackagePrice($event)"
                            @keydown="allowOnlyNumbers($event)"
                            inputmode="numeric"
                            autocomplete="off"
                            class="w-full px-4 py-2.5 max-sm:py-2 text-sm text-right bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-600 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 dark:focus:ring-rose-500/30 dark:focus:border-rose-400 transition @error('package_price') border-red-400 dark:border-red-400 @enderror"
                            placeholder="0"
                            required
                        >
                        <input type="hidden" name="package_price" :value="packagePrice">
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('package.package_price_help') }}</p>
                    @error('package_price')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Discount Preview -->
            <div class="mt-4 p-4 max-sm:p-3 bg-gray-50 dark:bg-gray-700 rounded-lg" x-show="originalPrice > 0 && packagePrice > 0">
                <div class="flex items-center justify-between text-sm max-sm:text-xs">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('common.discount') }}</span>
                    <span class="font-medium text-green-600 dark:text-green-400" x-text="originalPrice > 0 ? Math.round((1 - packagePrice / originalPrice) * 100) + '%' : '0%'"></span>
                </div>
                <div class="flex items-center justify-between text-sm max-sm:text-xs mt-1">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('package.customer_saves') }}</span>
                    <span class="font-medium text-green-600 dark:text-green-400" x-text="'Rp ' + (originalPrice - packagePrice).toLocaleString('id-ID')"></span>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" class="w-5 h-5 rounded border-gray-300 dark:border-gray-600 text-rose-500 focus:ring-rose-500" {{ old('is_active', true) ? 'checked' : '' }}>
                <div>
                    <p class="font-medium text-gray-900 dark:text-gray-100 text-sm max-sm:text-xs">{{ __('common.active') }}</p>
                    <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('package.can_be_offered') }}</p>
                </div>
            </label>
        </div>

        <!-- Submit -->
        <div class="flex flex-row max-sm:flex-col items-center gap-3">
            <button type="submit" class="px-6 py-2.5 max-sm:w-full max-sm:py-2 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm font-medium rounded-lg transition">
                {{ __('package.save_package') }}
            </button>
            <a href="{{ route('packages.index') }}" class="px-6 py-2.5 max-sm:w-full max-sm:py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                {{ __('common.cancel') }}
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('packagePricing', (initOriginal = 0, initPackage = 0) => ({
        originalPrice: initOriginal,
        packagePrice: initPackage,
        originalPriceDisplay: '',
        packagePriceDisplay: '',

        init() {
            this.originalPriceDisplay = this.formatNumber(initOriginal);
            this.packagePriceDisplay = this.formatNumber(initPackage);
        },

        allowOnlyNumbers(event) {
            const allowed = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter',
                'ArrowLeft', 'ArrowRight', 'Home', 'End'];
            if (allowed.includes(event.key)) return;
            if ((event.ctrlKey || event.metaKey) && ['a','c','v','x'].includes(event.key.toLowerCase())) return;
            if (!/^\d$/.test(event.key)) event.preventDefault();
        },

        updateOriginalPrice(event) {
            const raw = event.target.value.replace(/\D/g, '').replace(/^0+/, '') || '';
            this.originalPrice = parseInt(raw) || 0;
            this.originalPriceDisplay = this.formatNumber(raw);
        },

        updatePackagePrice(event) {
            const raw = event.target.value.replace(/\D/g, '').replace(/^0+/, '') || '';
            this.packagePrice = parseInt(raw) || 0;
            this.packagePriceDisplay = this.formatNumber(raw);
        },

        formatNumber(value) {
            if (!value || value === '0' || value === 0) return '';
            return new Intl.NumberFormat('id-ID').format(parseInt(value, 10));
        }
    }));
});
</script>
@endpush
