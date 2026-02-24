@extends('layouts.dashboard')

@section('title', __('transaction.add'))
@section('page-title', __('transaction.add'))

@section('content')
<div x-data="transactionForm()" class="max-w-6xl mx-auto space-y-6 max-sm:space-y-4">
    <!-- Back Button -->
    <div class="mb-6 max-sm:mb-4">
        <a href="{{ route('transactions.index') }}" class="inline-flex items-center gap-2 text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
            <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            {{ __('transaction.back_to_list') }}
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg text-sm max-sm:text-xs">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg text-sm max-sm:text-xs">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('transactions.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-3 max-lg:grid-cols-1 gap-6 max-sm:gap-4">
            <!-- Left Column: Items -->
            <div class="col-span-2 max-lg:col-span-1 space-y-6 max-sm:space-y-4">
                <!-- Customer Selection -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
                    <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('transaction.customer_info') }}</h3>

                    <div class="space-y-4 max-sm:space-y-3">
                        <div>
                            <label class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('transaction.customer') }} <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select
                                    name="customer_id"
                                    x-model="customerId"
                                    @change="loadCustomerPackages(); loadCustomerPoints()"
                                    class="w-full pl-4 pr-12 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition appearance-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    required
                                >
                                    <option value="">{{ __('transaction.select_customer') }}</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ (old('customer_id', $selectedCustomerId) == $customer->id) ? 'selected' : '' }}>
                                            {{ $customer->name }} - {{ $customer->phone }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                    <svg class="h-4 w-4 max-sm:h-3.5 max-sm:w-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        @if($appointment)
                            <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                            <div class="p-3 max-sm:p-2 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg">
                                <p class="text-sm max-sm:text-xs text-blue-700 dark:text-blue-400">
                                    <span class="font-medium">{{ __('transaction.from_appointment') }}:</span>
                                    {{ $appointment->service?->name ?? '-' }} - {{ format_datetime($appointment->appointment_date) }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Items -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
                    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between mb-4 max-sm:mb-3 gap-2 max-sm:gap-3">
                        <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('transaction.transaction_items') }}</h3>
                        <div class="flex gap-2 max-sm:w-full">
                            <button type="button" @click="addItem('service')" class="inline-flex items-center gap-1 px-3 max-sm:px-2 py-1.5 max-sm:py-1 bg-blue-100 text-blue-700 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-blue-200 transition max-sm:flex-1 max-sm:justify-center">
                                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                {{ __('transaction.add_service') }}
                            </button>
                            @if(has_feature('packages'))
                                <button type="button" @click="addItem('package')" class="inline-flex items-center gap-1 px-3 max-sm:px-2 py-1.5 max-sm:py-1 bg-purple-100 text-purple-700 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-purple-200 transition max-sm:flex-1 max-sm:justify-center">
                                    <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    {{ __('transaction.add_package') }}
                                </button>
                            @endif
                            <button type="button" @click="addItem('product')" class="inline-flex items-center gap-1 px-3 max-sm:px-2 py-1.5 max-sm:py-1 bg-amber-100 text-amber-700 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-amber-200 transition max-sm:flex-1 max-sm:justify-center">
                                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                {{ __('transaction.add_product') }}
                            </button>
                            <button type="button" @click="addItem('other')" class="inline-flex items-center gap-1 px-3 max-sm:px-2 py-1.5 max-sm:py-1 bg-gray-100 text-gray-700 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-gray-200 transition max-sm:flex-1 max-sm:justify-center">
                                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                {{ __('transaction.add_other') }}
                            </button>
                        </div>
                    </div>

                    <!-- Items List -->
                    <div class="space-y-4 max-sm:space-y-3">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="p-4 max-sm:p-3 border border-gray-200 dark:border-gray-600 rounded-lg">
                                <div class="flex items-start justify-between mb-3 max-sm:mb-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs max-sm:text-[10px] font-medium"
                                        :class="{
                                            'bg-blue-100 text-blue-700': item.item_type === 'service',
                                            'bg-purple-100 text-purple-700': item.item_type === 'package',
                                            'bg-amber-100 text-amber-700': item.item_type === 'product',
                                            'bg-green-100 text-green-700': item.item_type === 'customer_package',
                                            'bg-gray-100 text-gray-700': item.item_type === 'other'
                                        }"
                                        x-text="item.item_type === 'service' ? '{{ __('transaction.service') }}' : (item.item_type === 'package' ? '{{ __('transaction.new_package') }}' : (item.item_type === 'product' ? '{{ __('product.title') }}' : (item.item_type === 'customer_package' ? '{{ __('transaction.customer_package') }}' : '{{ __('transaction.add_other') }}')))">
                                    </span>
                                    <button type="button" @click="removeItem(index)" class="text-gray-400 hover:text-red-500">
                                        <svg class="w-5 h-5 max-sm:w-4 max-sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <input type="hidden" :name="'items[' + index + '][item_type]'" :value="item.item_type">

                                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 max-sm:gap-3">
                                    <!-- Service Selection -->
                                    <div class="md:col-span-2" x-show="item.item_type === 'service'">
                                        <label class="block text-xs max-sm:text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-1 max-sm:mb-0.5">{{ __('transaction.select_service') }}</label>
                                        <select
                                            :name="'items[' + index + '][service_id]'"
                                            x-model="item.service_id"
                                            @change="selectService(index)"
                                            class="w-full px-3 py-2 max-sm:py-1.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                        >
                                            <option value="">{{ __('transaction.select_service') }}</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}" data-name="{{ $service->name }}" data-price="{{ $service->price }}">
                                                    {{ $service->name }} - {{ $service->formatted_price }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @if(has_feature('packages'))
                                        <!-- Package Selection -->
                                        <div class="md:col-span-2" x-show="item.item_type === 'package'">
                                            <label class="block text-xs max-sm:text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-1 max-sm:mb-0.5">{{ __('transaction.select_package') }}</label>
                                            <select
                                                :name="'items[' + index + '][package_id]'"
                                                x-model="item.package_id"
                                                @change="selectPackage(index)"
                                                class="w-full px-3 py-2 max-sm:py-1.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                            >
                                                <option value="">{{ __('transaction.select_package') }}</option>
                                                @foreach($packages as $package)
                                                    <option value="{{ $package->id }}" data-name="{{ $package->name }}" data-price="{{ $package->package_price }}">
                                                        {{ $package->name }} - {{ $package->formatted_package_price }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <!-- Product Selection -->
                                    <div class="md:col-span-2" x-show="item.item_type === 'product'">
                                        <label class="block text-xs max-sm:text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-1 max-sm:mb-0.5">{{ __('transaction.select_product') }}</label>
                                        <select
                                            :name="'items[' + index + '][product_id]'"
                                            x-model="item.product_id"
                                            @change="selectProduct(index)"
                                            class="w-full px-3 py-2 max-sm:py-1.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                        >
                                            <option value="">{{ __('transaction.select_product') }}</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}" data-track="{{ $product->track_stock ? 1 : 0 }}">
                                                    {{ $product->name }} - {{ $product->formatted_price }}
                                                    @if($product->track_stock) (Stok: {{ $product->stock }}) @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <p x-show="item.product_id && item.stock !== null && item.track_stock" class="mt-1 text-xs" :class="item.quantity > item.stock ? 'text-red-500' : 'text-gray-500'">
                                            {{ __('product.stock') }}: <span x-text="item.stock"></span>
                                        </p>
                                    </div>

                                    @if(has_feature('customer_packages'))
                                        <!-- Customer Package Selection -->
                                        <div class="md:col-span-2" x-show="item.item_type === 'customer_package'">
                                            <label class="block text-xs max-sm:text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-1 max-sm:mb-0.5">{{ __('transaction.select_customer_package') }}</label>
                                            <select
                                                :name="'items[' + index + '][customer_package_id]'"
                                                x-model="item.customer_package_id"
                                                @change="selectCustomerPackage(index)"
                                                class="w-full px-3 py-2 max-sm:py-1.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                            >
                                                <option value="">{{ __('transaction.select_customer_package') }}</option>
                                                <template x-for="cp in customerPackages" :key="cp.id">
                                                    <option :value="cp.id" x-text="cp.package.name + ' (' + cp.sessions_remaining + ' {{ __('transaction.sessions_remaining') }})'"></option>
                                                </template>
                                            </select>
                                        </div>
                                    @endif

                                    <!-- Item Name -->
                                    <div class="md:col-span-2" x-show="item.item_type === 'other'">
                                        <label class="block text-xs max-sm:text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-1 max-sm:mb-0.5">{{ __('transaction.item_name') }}</label>
                                        <input
                                            type="text"
                                            :name="'items[' + index + '][item_name]'"
                                            x-model="item.item_name"
                                            placeholder="{{ __('transaction.item_name') }}"
                                            class="w-full px-3 py-2 max-sm:py-1.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                        >
                                    </div>

                                    <!-- Hidden Item Name for non-other types -->
                                    <input type="hidden" :name="'items[' + index + '][item_name]'" :value="item.item_name" x-show="item.item_type !== 'other'">

                                    <!-- Quantity -->
                                    <div>
                                        <label class="block text-xs max-sm:text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-1 max-sm:mb-0.5">{{ __('transaction.quantity') }}</label>
                                        <input
                                            type="number"
                                            :name="'items[' + index + '][quantity]'"
                                            x-model.number="item.quantity"
                                            min="1"
                                            class="w-full px-3 py-2 max-sm:py-1.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                        >
                                    </div>

                                    <!-- Unit Price -->
                                    <div>
                                        <label class="block text-xs max-sm:text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-1 max-sm:mb-0.5">{{ __('transaction.price') }}</label>
                                        <input
                                            type="text"
                                            :value="formatNumber(item.unit_price)"
                                            @input="item.unit_price = parseNumber($event.target.value); $event.target.value = formatNumber(item.unit_price)"
                                            inputmode="numeric"
                                            class="w-full px-3 py-2 max-sm:py-1.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                            :readonly="item.item_type === 'customer_package'"
                                        >
                                        <input type="hidden" :name="'items[' + index + '][unit_price]'" :value="item.unit_price">
                                    </div>

                                    <!-- Discount -->
                                    <div>
                                        <label class="block text-xs max-sm:text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-1 max-sm:mb-0.5">{{ __('transaction.discount') }}</label>
                                        <input
                                            type="text"
                                            :value="formatNumber(item.discount)"
                                            @input="item.discount = parseNumber($event.target.value); $event.target.value = formatNumber(item.discount)"
                                            inputmode="numeric"
                                            class="w-full px-3 py-2 max-sm:py-1.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                        >
                                        <input type="hidden" :name="'items[' + index + '][discount]'" :value="item.discount">
                                    </div>

                                    <!-- Subtotal (Display) -->
                                    <div>
                                        <label class="block text-xs max-sm:text-[10px] font-medium text-gray-500 dark:text-gray-400 mb-1 max-sm:mb-0.5">{{ __('transaction.subtotal') }}</label>
                                        <div class="px-3 py-2 max-sm:py-1.5 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-900 dark:text-gray-100" x-text="formatRupiah(getItemSubtotal(item))">
                                        </div>
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div class="mt-3 max-sm:mt-2">
                                    <input
                                        type="text"
                                        :name="'items[' + index + '][notes]'"
                                        x-model="item.notes"
                                        placeholder="Catatan item (opsional)"
                                        class="w-full px-3 py-2 max-sm:py-1.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    >
                                </div>
                            </div>
                        </template>

                        <!-- Empty State -->
                        <div x-show="items.length === 0" class="p-8 max-sm:p-6 text-center border-2 border-dashed border-gray-200 dark:border-gray-600 rounded-lg">
                            <svg class="w-12 h-12 max-sm:w-10 max-sm:h-10 text-gray-300 dark:text-gray-600 mx-auto mb-3 max-sm:mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 text-sm max-sm:text-xs">Belum ada item. Klik tombol di atas untuk menambahkan.</p>
                        </div>
                    </div>

                    @if(has_feature('customer_packages'))
                        <!-- Customer Packages Available -->
                        <div x-show="customerPackages.length > 0" class="mt-4 max-sm:mt-3 p-4 max-sm:p-3 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg">
                            <p class="text-sm max-sm:text-xs text-green-700 dark:text-green-400 font-medium mb-2">Paket Aktif Customer:</p>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="cp in customerPackages" :key="cp.id">
                                    <button
                                        type="button"
                                        @click="addCustomerPackageItem(cp)"
                                        class="inline-flex items-center gap-1 px-3 max-sm:px-2 py-1.5 max-sm:py-1 bg-white border border-green-300 text-green-700 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-green-100 transition"
                                    >
                                        <span x-text="cp.package.name"></span>
                                        <span class="text-xs max-sm:text-[10px]" x-text="'(' + cp.sessions_remaining + ' sesi)'"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Summary -->
            <div class="space-y-6 max-sm:space-y-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4 sticky top-6">
                    <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">Ringkasan</h3>

                    <div class="space-y-3 max-sm:space-y-2 mb-6 max-sm:mb-4">
                        <div class="flex justify-between text-sm max-sm:text-xs">
                            <span class="text-gray-500 dark:text-gray-400">{{ __('transaction.subtotal') }}</span>
                            <span class="font-medium text-gray-900 dark:text-gray-100" x-text="formatRupiah(subtotal)"></span>
                        </div>
                        <div class="flex items-center justify-between text-sm max-sm:text-xs">
                            <span class="text-gray-500 dark:text-gray-400">Diskon Tambahan</span>
                            <div class="relative">
                                <input
                                    type="text"
                                    x-model="discountAmountDisplay"
                                    @input="discountAmount = parseNumber($event.target.value); discountAmountDisplay = formatNumber(discountAmount)"
                                    inputmode="numeric"
                                    class="w-28 max-sm:w-24 px-3 max-sm:px-2 py-1.5 max-sm:py-1 border border-gray-200 dark:border-gray-600 rounded-lg text-sm max-sm:text-xs text-right focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                >
                                <input type="hidden" name="discount_amount" :value="discountAmount">
                            </div>
                        </div>
                        <div x-show="discountAmount > 0">
                            <input
                                type="text"
                                name="discount_type"
                                x-model="discountType"
                                placeholder="Keterangan diskon"
                                class="w-full px-3 max-sm:px-2 py-1.5 max-sm:py-1 border border-gray-200 dark:border-gray-600 rounded-lg text-sm max-sm:text-xs focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                            >
                        </div>

                        <!-- Use Loyalty Points -->
                        <div x-show="customerPoints > 0" class="p-3 max-sm:p-2 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm max-sm:text-xs font-medium text-amber-700 dark:text-amber-300">{{ __('loyalty.use_points') }}</span>
                                </div>
                                <span class="text-xs text-amber-600 dark:text-amber-400">
                                    {{ __('loyalty.available') }}: <span x-text="formatNumber(customerPoints)"></span> {{ __('loyalty.points') }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" x-model="usePoints" @change="onTogglePoints()" class="sr-only peer">
                                    <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-amber-300 dark:peer-focus:ring-amber-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-500 peer-checked:bg-amber-500"></div>
                                </label>
                                <div x-show="usePoints" class="flex-1">
                                    <input
                                        type="text"
                                        :value="pointsToUse > 0 ? formatNumber(pointsToUse) : ''"
                                        @input="updatePointsToUse($event.target.value)"
                                        @blur="validatePoints()"
                                        inputmode="numeric"
                                        placeholder="Jumlah poin"
                                        class="w-full px-3 max-sm:px-2 py-1.5 max-sm:py-1 border border-amber-300 dark:border-amber-600 rounded-lg text-sm max-sm:text-xs focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    >
                                </div>
                            </div>
                            <div x-show="usePoints && pointsToUse > 0" class="mt-2 flex items-center justify-between text-xs">
                                <span class="text-amber-600 dark:text-amber-400">
                                    <span x-text="formatNumber(pointsToUse)"></span> {{ __('loyalty.points') }} = <span x-text="formatRupiah(pointsDiscount)"></span>
                                </span>
                                <button type="button" @click="useMaxPoints()" class="text-amber-700 dark:text-amber-300 hover:underline font-medium">
                                    {{ __('loyalty.use_max') }}
                                </button>
                            </div>
                            <input type="hidden" name="points_used" :value="usePoints ? pointsToUse : 0">
                        </div>

                        <div x-show="pointsDiscount > 0" class="flex justify-between text-sm max-sm:text-xs text-amber-600 dark:text-amber-400">
                            <span>{{ __('loyalty.points_discount') }}</span>
                            <span x-text="'-' + formatRupiah(pointsDiscount)"></span>
                        </div>

                        <div class="flex items-center justify-between text-sm max-sm:text-xs">
                            <span class="text-gray-500 dark:text-gray-400">{{ __('transaction.tax') }}</span>
                            <div class="relative">
                                <input
                                    type="text"
                                    x-model="taxAmountDisplay"
                                    @input="taxAmount = parseNumber($event.target.value); taxAmountDisplay = formatNumber(taxAmount)"
                                    inputmode="numeric"
                                    class="w-28 max-sm:w-24 px-3 max-sm:px-2 py-1.5 max-sm:py-1 border border-gray-200 dark:border-gray-600 rounded-lg text-sm max-sm:text-xs text-right focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                >
                                <input type="hidden" name="tax_amount" :value="taxAmount">
                            </div>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-3 max-sm:pt-2">
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-900 dark:text-gray-100 text-sm max-sm:text-xs">{{ __('common.total') }}</span>
                                <span class="font-bold text-lg max-sm:text-base text-rose-600 dark:text-rose-400" x-text="formatRupiah(total)"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-6 max-sm:mb-4">
                        <label class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('transaction.notes') }}</label>
                        <textarea
                            name="notes"
                            rows="3"
                            class="w-full px-4 max-sm:px-3 py-2 max-sm:py-1.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                            placeholder="{{ __('transaction.transaction_notes') }} ({{ __('common.optional') }})"
                        >{{ old('notes') }}</textarea>
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        :disabled="items.length === 0 || !customerId"
                        class="w-full px-4 py-3 max-sm:py-2 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white font-medium text-sm rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Buat Transaksi
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
@php
    $defaultItems = [];
    if ($appointment && $appointment->service) {
        $defaultItems = [[
            'item_type' => 'service',
            'service_id' => $appointment->service_id,
            'package_id' => null,
            'customer_package_id' => null,
            'item_name' => $appointment->service->name,
            'quantity' => 1,
            'unit_price' => $appointment->service->price,
            'discount' => 0,
            'notes' => ''
        ]];
    }
    $initialItems = old('items', $defaultItems);
@endphp
<script>
    function transactionForm() {
        return {
            customerId: '{{ old('customer_id', $appointment?->customer_id ?? $selectedCustomerId ?? '') }}',
            items: @json($initialItems),
            customerPackages: [],
            hasCustomerPackages: @json(has_feature('customer_packages')),
            discountAmount: {{ old('discount_amount', 0) }},
            discountAmountDisplay: '',
            discountType: '{{ old('discount_type', '') }}',
            taxAmount: {{ old('tax_amount', 0) }},
            taxAmountDisplay: '',

            // Loyalty points
            customerPoints: 0,
            usePoints: false,
            pointsToUse: 0,
            pointsValue: {{ config('loyalty.points_value', 100) }},
            minPointsRedeem: {{ config('loyalty.min_points_redeem', 10) }},

            init() {
                this.discountAmountDisplay = this.formatNumber(this.discountAmount);
                this.taxAmountDisplay = this.formatNumber(this.taxAmount);
                if (this.customerId) {
                    if (this.hasCustomerPackages) {
                        this.loadCustomerPackages();
                    }
                    this.loadCustomerPoints();
                }
            },

            get subtotal() {
                return this.items.reduce((sum, item) => sum + this.getItemSubtotal(item), 0);
            },

            get pointsDiscount() {
                if (!this.usePoints || this.pointsToUse <= 0) return 0;
                return this.pointsToUse * this.pointsValue;
            },

            get maxPointsToUse() {
                // Max points = minimum of (customer points, subtotal / points_value)
                const maxBySubtotal = Math.floor((this.subtotal - this.discountAmount) / this.pointsValue);
                return Math.min(this.customerPoints, Math.max(0, maxBySubtotal));
            },

            get total() {
                return Math.max(0, this.subtotal - this.discountAmount - this.pointsDiscount + this.taxAmount);
            },

            getItemSubtotal(item) {
                return (item.unit_price * item.quantity) - (item.discount || 0);
            },

            formatRupiah(value) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
            },

            formatNumber(value) {
                if (!value || value === 0) return '';
                return new Intl.NumberFormat('id-ID').format(value);
            },

            parseNumber(value) {
                return parseInt(String(value).replace(/\D/g, '')) || 0;
            },

            addItem(type) {
                this.items.push({
                    item_type: type,
                    service_id: null,
                    package_id: null,
                    product_id: null,
                    customer_package_id: null,
                    item_name: '',
                    quantity: 1,
                    unit_price: 0,
                    discount: 0,
                    notes: '',
                    stock: null,
                    track_stock: false
                });
            },

            addCustomerPackageItem(cp) {
                const exists = this.items.find(item =>
                    item.item_type === 'customer_package' && item.customer_package_id == cp.id
                );

                if (exists) {
                    alert('Paket ini sudah ditambahkan');
                    return;
                }

                this.items.push({
                    item_type: 'customer_package',
                    service_id: null,
                    package_id: null,
                    customer_package_id: cp.id,
                    item_name: cp.package.name + ' (Pakai Sesi)',
                    quantity: 1,
                    unit_price: 0,
                    discount: 0,
                    notes: ''
                });
            },

            removeItem(index) {
                this.items.splice(index, 1);
            },

            selectService(index) {
                const select = document.querySelector(`select[name="items[${index}][service_id]"]`);
                const option = select.options[select.selectedIndex];
                if (option && option.value) {
                    this.items[index].item_name = option.dataset.name;
                    this.items[index].unit_price = parseFloat(option.dataset.price) || 0;
                }
            },

            selectPackage(index) {
                const select = document.querySelector(`select[name="items[${index}][package_id]"]`);
                const option = select.options[select.selectedIndex];
                if (option && option.value) {
                    this.items[index].item_name = option.dataset.name;
                    this.items[index].unit_price = parseFloat(option.dataset.price) || 0;
                }
            },

            selectProduct(index) {
                const select = document.querySelector(`select[name="items[${index}][product_id]"]`);
                const option = select.options[select.selectedIndex];
                if (option && option.value) {
                    this.items[index].item_name = option.dataset.name;
                    this.items[index].unit_price = parseFloat(option.dataset.price) || 0;
                    this.items[index].stock = parseInt(option.dataset.stock) || 0;
                    this.items[index].track_stock = option.dataset.track === '1';
                }
            },

            selectCustomerPackage(index) {
                const cp = this.customerPackages.find(p => p.id == this.items[index].customer_package_id);
                if (cp) {
                    this.items[index].item_name = cp.package.name + ' (Pakai Sesi)';
                    this.items[index].unit_price = 0;
                }
            },

            async loadCustomerPackages() {
                if (! this.hasCustomerPackages) {
                    this.customerPackages = [];
                    return;
                }
                if (!this.customerId) {
                    this.customerPackages = [];
                    return;
                }

                try {
                    const response = await fetch(`/api/customers/${this.customerId}/packages`);
                    const data = await response.json();
                    this.customerPackages = data;
                } catch (error) {
                    console.error('Failed to load customer packages:', error);
                    this.customerPackages = [];
                }
            },

            async loadCustomerPoints() {
                if (!this.customerId) {
                    this.customerPoints = 0;
                    this.resetPoints();
                    return;
                }

                try {
                    const response = await fetch(`/api/customers/${this.customerId}/points`);
                    const data = await response.json();
                    this.customerPoints = data.points || 0;
                    this.resetPoints();
                } catch (error) {
                    console.error('Failed to load customer points:', error);
                    this.customerPoints = 0;
                }
            },

            resetPoints() {
                this.usePoints = false;
                this.pointsToUse = 0;
            },

            onTogglePoints() {
                if (this.usePoints) {
                    // When enabled, set to max points by default
                    this.pointsToUse = this.maxPointsToUse;
                } else {
                    this.pointsToUse = 0;
                }
            },

            updatePointsToUse(value) {
                // Parse the input value (remove non-digits)
                const parsed = parseInt(String(value).replace(/\D/g, '')) || 0;
                this.pointsToUse = parsed;
            },

            validatePoints() {
                // Validate on blur
                if (this.pointsToUse < 0) {
                    this.pointsToUse = 0;
                }
                if (this.pointsToUse > this.maxPointsToUse) {
                    this.pointsToUse = this.maxPointsToUse;
                }
            },

            useMaxPoints() {
                this.pointsToUse = this.maxPointsToUse;
            }
        };
    }
</script>
@endpush
@endsection
