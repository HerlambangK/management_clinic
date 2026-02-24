@extends('layouts.dashboard')

@section('title', __('customer_package.add'))
@section('page-title', __('customer_package.add'))

@section('content')
<div class="max-w-4xl mx-auto" x-data="packageForm()">
    <!-- Back Button -->
    <a href="{{ route('customer-packages.index') }}" class="inline-flex items-center text-sm max-sm:text-xs text-gray-500 hover:text-gray-700 mb-6 max-sm:mb-4">
        <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        {{ __('common.back') }}
    </a>

    <form action="{{ route('customer-packages.store') }}" method="POST" class="space-y-6 max-sm:space-y-4">
        @csrf

        <!-- Customer Selection -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-white mb-4 max-sm:mb-3">{{ __('customer_package.customer') }}</h3>

            <div>
                <label for="customer_id" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('customer_package.select_customer') }} <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select
                        id="customer_id"
                        name="customer_id"
                        class="w-full pl-4 pr-12 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition appearance-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('customer_id') border-red-400 @enderror"
                        required
                    >
                        <option value="">{{ __('customer_package.select_customer') }}</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $selectedCustomerId) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} - {{ $customer->phone }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                @error('customer_id')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Package Selection -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-white mb-4 max-sm:mb-3">{{ __('customer_package.package') }}</h3>

            <div>
                <label for="package_id" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('customer_package.select_package') }} <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select
                        id="package_id"
                        name="package_id"
                        x-model="selectedPackageId"
                        @change="loadPackageDetails()"
                        class="w-full pl-4 pr-12 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition appearance-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('package_id') border-red-400 @enderror"
                        required
                    >
                        <option value="">{{ __('customer_package.select_package') }}</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" data-price="{{ $package->package_price }}" data-sessions="{{ $package->total_sessions }}" data-validity="{{ $package->validity_days }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                {{ $package->name }} - {{ $package->formatted_package_price }} ({{ $package->total_sessions }}x {{ __('customer_package.sessions') }})
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                @error('package_id')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Package Details -->
            <div x-show="packageDetails" x-cloak class="mt-4 p-4 max-sm:p-3 bg-rose-50 dark:bg-rose-900/20 rounded-lg">
                <div class="grid sm:grid-cols-3 gap-4 max-sm:gap-3 text-sm max-sm:text-xs">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">{{ __('customer_package.total_sessions') }}</p>
                        <p class="font-medium text-gray-900 dark:text-white" x-text="packageDetails?.total_sessions + 'x'"></p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">{{ __('customer_package.validity_period') }}</p>
                        <p class="font-medium text-gray-900 dark:text-white" x-text="packageDetails?.validity_days + ' {{ __('common.days') }}'"></p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">{{ __('common.price') }}</p>
                        <p class="font-medium text-rose-600 dark:text-rose-400" x-text="packageDetails?.formatted_price"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Purchase Details -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-white mb-4 max-sm:mb-3">{{ __('customer_package.purchase_details') }}</h3>

            <div class="grid sm:grid-cols-2 gap-4 max-sm:gap-3">
                <div>
                    <label for="purchased_at" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('customer_package.purchase_date') }} <span class="text-red-500">*</span></label>
                    <input
                        type="date"
                        id="purchased_at"
                        name="purchased_at"
                        value="{{ old('purchased_at', today()->format('Y-m-d')) }}"
                        max="{{ today()->format('Y-m-d') }}"
                        class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('purchased_at') border-red-400 @enderror"
                        required
                    >
                    @error('purchased_at')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price_paid_display" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('customer_package.price_paid') }} <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">Rp</span>
                        <input
                            type="text"
                            id="price_paid_display"
                            x-model="pricePaidDisplay"
                            @input="updatePricePaid($event)"
                            inputmode="numeric"
                            class="w-full pl-12 pr-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('price_paid') border-red-400 @enderror"
                            required
                        >
                        <input type="hidden" name="price_paid" :value="pricePaid">
                    </div>
                    @error('price_paid')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <label for="notes" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('common.notes') }}</label>
                <textarea
                    id="notes"
                    name="notes"
                    rows="2"
                    class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('notes') border-red-400 @enderror"
                    placeholder="{{ __('customer_package.notes') }}..."
                >{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Submit -->
        <div class="flex flex-row max-sm:flex-col items-center gap-3">
            <button type="submit" class="px-6 py-2.5 max-sm:w-full max-sm:py-2 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm font-medium rounded-lg transition">
                {{ __('customer_package.save_purchase') }}
            </button>
            <a href="{{ route('customer-packages.index') }}" class="px-6 py-2.5 max-sm:w-full max-sm:py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition text-center">
                {{ __('common.cancel') }}
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function packageForm() {
    return {
        selectedPackageId: '{{ old('package_id') }}',
        packageDetails: null,
        pricePaid: {{ old('price_paid', 0) }},
        pricePaidDisplay: '',

        formatNumber(value) {
            if (!value || value === '0' || value === 0) return '';
            return new Intl.NumberFormat('id-ID').format(value);
        },

        updatePricePaid(event) {
            const raw = event.target.value.replace(/\D/g, '');
            this.pricePaid = parseInt(raw) || 0;
            this.pricePaidDisplay = this.formatNumber(raw);
        },

        async loadPackageDetails() {
            if (!this.selectedPackageId) {
                this.packageDetails = null;
                this.pricePaid = 0;
                this.pricePaidDisplay = '';
                return;
            }

            try {
                const response = await fetch(`/api/packages/${this.selectedPackageId}`);
                this.packageDetails = await response.json();
                this.pricePaid = this.packageDetails.package_price;
                this.pricePaidDisplay = this.formatNumber(this.pricePaid);
            } catch (error) {
                console.error('Error loading package details:', error);
            }
        },

        init() {
            this.pricePaidDisplay = this.formatNumber(this.pricePaid);
            if (this.selectedPackageId) {
                this.loadPackageDetails();
            }
        }
    }
}
</script>
@endpush
@endsection
