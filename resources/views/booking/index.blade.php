@extends('layouts.landing')

@php
    $businessType = business_type();
    $bookingTitle = __('booking.title_' . $businessType, [], null) !== 'booking.title_' . $businessType
        ? __('booking.title_' . $businessType)
        : __('booking.title');
    $bookingSubtitle = __('booking.subtitle_' . $businessType, [], null) !== 'booking.subtitle_' . $businessType
        ? __('booking.subtitle_' . $businessType)
        : __('booking.subtitle');
    $stepService = __('booking.step_service_' . $businessType, [], null) !== 'booking.step_service_' . $businessType
        ? __('booking.step_service_' . $businessType)
        : __('booking.step_service');
    $selectService = __('booking.select_service_' . $businessType, [], null) !== 'booking.select_service_' . $businessType
        ? __('booking.select_service_' . $businessType)
        : __('booking.select_service');
    $selectedService = __('booking.selected_service_' . $businessType, [], null) !== 'booking.selected_service_' . $businessType
        ? __('booking.selected_service_' . $businessType)
        : __('booking.selected_service');
    $preferredStaff = __('booking.preferred_staff_' . $businessType, [], null) !== 'booking.preferred_staff_' . $businessType
        ? __('booking.preferred_staff_' . $businessType)
        : __('booking.preferred_staff');
@endphp

@section('title', $bookingTitle . ' - ' . brand_name())

@section('content')
<div class="relative min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto relative z-10">
        <!-- Header -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6">
                @if(brand_logo())
                    <img src="{{ brand_logo() }}" alt="{{ brand_name() }}" class="h-10 w-auto">
                @else
                    <div class="w-10 h-10 bg-gradient-to-br {{ $tc->gradient ?? 'from-rose-400 to-rose-500' }} rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>
                @endif
                <span class="text-xl font-bold text-gray-900">{{ brand_name() }}</span>
            </a>
            <h1 class="text-3xl font-serif font-bold text-gray-900">{{ $bookingTitle }}</h1>
            <p class="mt-2 text-gray-600">{{ $bookingSubtitle }}</p>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Booking Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8 max-sm:p-6" x-data="bookingForm()">
            <form action="{{ route('booking.store') }}" method="POST" @submit="validateForm">
                @csrf

                <!-- Step Indicator -->
                <div class="flex items-center justify-center mb-8">
                    <template x-for="(label, index) in ['{{ $stepService }}', '{{ __('booking.step_datetime') }}', '{{ __('booking.step_contact') }}']" :key="index">
                        <div class="flex items-center">
                            <div :class="step > index ? '{{ $tc->button ?? 'bg-rose-500' }} text-white' : (step === index + 1 ? '{{ $tc->button ?? 'bg-rose-500' }} text-white' : 'bg-gray-200 text-gray-500')"
                                 class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium transition-colors">
                                <span x-text="index + 1"></span>
                            </div>
                            <span :class="step >= index + 1 ? '{{ $tc->linkDark ?? 'text-rose-600' }}' : 'text-gray-400'" class="ml-2 text-sm font-medium max-sm:hidden" x-text="label"></span>
                            <div x-show="index < 2" class="w-8 h-px bg-gray-300 mx-3 max-sm:mx-2"></div>
                        </div>
                    </template>
                </div>

                <!-- Step 1: Select Service -->
                <div x-show="step === 1" x-transition>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $selectService }}</h3>

                    <div class="space-y-4">
                        @foreach($categories as $category)
                            @if($category->services->count() > 0)
                                <div class="border border-gray-200 rounded-xl overflow-hidden">
                                    <button type="button"
                                            @click="toggleCategory({{ $category->id }})"
                                            class="w-full px-4 py-3 flex items-center justify-between bg-gray-50 hover:bg-gray-100 transition">
                                        <span class="font-medium text-gray-900">{{ $category->name }}</span>
                                        <svg :class="openCategories.includes({{ $category->id }}) ? 'rotate-180' : ''" class="w-5 h-5 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div x-show="openCategories.includes({{ $category->id }})" x-collapse class="border-t border-gray-200">
                                        @foreach($category->services as $service)
                                            <label class="flex items-start gap-4 p-4 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0">
                                                <input type="radio" name="service_id" value="{{ $service->id }}"
                                                       x-model="serviceId"
                                                       @change="selectService({{ $service->id }}, '{{ $service->name }}', {{ $service->duration_minutes }}, {{ $service->price }})"
                                                       class="mt-1 {{ $tc->radio ?? 'text-rose-500' }} focus:ring-{{ $tc->primary ?? 'rose' }}-500">
                                                <div class="flex-1">
                                                    <div class="flex items-start justify-between">
                                                        <div>
                                                            <p class="font-medium text-gray-900">{{ $service->name }}</p>
                                                            <p class="text-sm text-gray-500">{{ $service->duration_minutes }} {{ __('common.minutes') }}</p>
                                                        </div>
                                                        <p class="font-semibold {{ $tc->linkDark ?? 'text-rose-600' }}">{{ $service->formatted_price }}</p>
                                                    </div>
                                                    @if($service->description)
                                                        <p class="mt-1 text-sm text-gray-500">{{ Str::limit($service->description, 100) }}</p>
                                                    @endif
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    @error('service_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="mt-6 flex justify-end">
                        <button type="button" @click="nextStep()" :disabled="!serviceId"
                                :class="serviceId ? '{{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }}' : 'bg-gray-300 cursor-not-allowed'"
                                class="px-6 py-2.5 text-white font-medium rounded-lg transition">
                            {{ __('common.next') }}
                            <svg class="inline w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Select Date & Time -->
                <div x-show="step === 2" x-transition>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('booking.select_datetime') }}</h3>

                    <!-- Selected Service Summary -->
                    <div class="mb-6 p-4 {{ $tc->bgLight ?? 'bg-rose-50' }} rounded-lg">
                        <p class="text-sm text-gray-600">{{ $selectedService }}:</p>
                        <p class="font-semibold text-gray-900" x-text="serviceName"></p>
                        <p class="text-sm text-gray-500">
                            <span x-text="serviceDuration"></span> {{ __('common.minutes') }} -
                            <span class="{{ $tc->linkDark ?? 'text-rose-600' }}" x-text="'Rp ' + servicePrice.toLocaleString('id-ID')"></span>
                        </p>
                    </div>

                    <div class="grid grid-cols-2 max-sm:grid-cols-1 gap-6">
                        <!-- Date Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('booking.appointment_date') }}</label>
                            <input type="date" name="appointment_date" x-model="appointmentDate"
                                   @change="fetchSlots()"
                                   min="{{ now()->format('Y-m-d') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400">
                            @error('appointment_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Staff Selection (Optional) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $preferredStaff }} <span class="text-gray-400">({{ __('common.optional') }})</span></label>
                            <select name="staff_id" x-model="staffId" @change="fetchSlots()"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400">
                                <option value="">{{ __('booking.any_available') }}</option>
                                @foreach($beauticians as $beautician)
                                    <option value="{{ $beautician->id }}">{{ $beautician->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Time Slots -->
                    <div class="mt-6" x-show="appointmentDate">
                        <label class="block text-sm font-medium text-gray-700 mb-3">{{ __('booking.select_time') }}</label>

                        <div x-show="loadingSlots" class="text-center py-8">
                            <svg class="animate-spin h-8 w-8 mx-auto {{ $tc->linkDark ?? 'text-rose-500' }}" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <p class="mt-2 text-gray-500">{{ __('booking.loading_slots') }}</p>
                        </div>

                        <div x-show="!loadingSlots && slots.length === 0" class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ __('booking.no_slots') }}
                        </div>

                        <div x-show="!loadingSlots && slots.length > 0" class="space-y-4">
                            <!-- Morning Slots -->
                            <div x-show="morningSlots.length > 0">
                                <p class="text-sm text-gray-500 mb-2">{{ __('booking.morning') }}</p>
                                <div class="grid grid-cols-4 max-sm:grid-cols-3 gap-2">
                                    <template x-for="slot in morningSlots" :key="slot">
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="start_time" :value="slot" x-model="startTime" class="peer sr-only">
                                            <div class="px-3 py-2 text-center text-sm border border-gray-200 rounded-lg peer-checked:{{ $tc->borderActive ?? 'border-rose-500' }} peer-checked:{{ $tc->bgLight ?? 'bg-rose-50' }} peer-checked:{{ $tc->linkDark ?? 'text-rose-600' }} hover:bg-gray-50 transition"
                                                 x-text="slot"></div>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <!-- Afternoon Slots -->
                            <div x-show="afternoonSlots.length > 0">
                                <p class="text-sm text-gray-500 mb-2">{{ __('booking.afternoon') }}</p>
                                <div class="grid grid-cols-4 max-sm:grid-cols-3 gap-2">
                                    <template x-for="slot in afternoonSlots" :key="slot">
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="start_time" :value="slot" x-model="startTime" class="peer sr-only">
                                            <div class="px-3 py-2 text-center text-sm border border-gray-200 rounded-lg peer-checked:{{ $tc->borderActive ?? 'border-rose-500' }} peer-checked:{{ $tc->bgLight ?? 'bg-rose-50' }} peer-checked:{{ $tc->linkDark ?? 'text-rose-600' }} hover:bg-gray-50 transition"
                                                 x-text="slot"></div>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </div>

                        @error('start_time')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6 flex justify-between">
                        <button type="button" @click="prevStep()" class="px-6 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            {{ __('common.back') }}
                        </button>
                        <button type="button" @click="nextStep()" :disabled="!startTime"
                                :class="startTime ? '{{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }}' : 'bg-gray-300 cursor-not-allowed'"
                                class="px-6 py-2.5 text-white font-medium rounded-lg transition">
                            {{ __('common.next') }}
                            <svg class="inline w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Contact Details -->
                <div x-show="step === 3" x-transition>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('booking.contact_details') }}</h3>

                    <!-- Booking Summary -->
                    <div class="mb-6 p-4 {{ $tc->bgLight ?? 'bg-rose-50' }} rounded-lg">
                        <p class="font-semibold text-gray-900" x-text="serviceName"></p>
                        <p class="text-sm text-gray-600">
                            <span x-text="formatDate(appointmentDate)"></span> {{ __('common.at') }} <span x-text="startTime"></span>
                        </p>
                    </div>

                    <!-- Logged in customer notice -->
                    @if($loggedInCustomer)
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm text-green-700">{{ __('booking.logged_in_as', ['name' => $loggedInCustomer->name]) }}</span>
                    </div>
                    @endif

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('booking.your_name') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="customerName" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400 {{ $loggedInCustomer ? 'bg-gray-50' : '' }}"
                                   placeholder="{{ __('booking.name_placeholder') }}"
                                   {{ $loggedInCustomer ? 'readonly' : '' }}>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('booking.phone_number') }} <span class="text-red-500">*</span></label>
                            <input type="tel" name="phone" x-model="customerPhone" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400 {{ $loggedInCustomer ? 'bg-gray-50' : '' }}"
                                   placeholder="08xxxxxxxxxx"
                                   {{ $loggedInCustomer ? 'readonly' : '' }}>
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('common.email') }} <span class="text-gray-400">({{ __('common.optional') }})</span></label>
                            <input type="email" name="email" x-model="customerEmail"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400 {{ $loggedInCustomer ? 'bg-gray-50' : '' }}"
                                   placeholder="email@example.com"
                                   {{ $loggedInCustomer ? 'readonly' : '' }}>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('booking.notes') }} <span class="text-gray-400">({{ __('common.optional') }})</span></label>
                            <textarea name="notes" x-model="notes" rows="3"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400"
                                      placeholder="{{ __('booking.notes_placeholder') }}"></textarea>
                        </div>

                        @if(config('referral.enabled', true))
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('booking.referral_code') }} <span class="text-gray-400">({{ __('common.optional') }})</span></label>
                            <input type="text" name="referral_code" x-model="referralCode"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 {{ $tc->ring ?? 'focus:ring-rose-500/20' }} focus:border-{{ $tc->primary ?? 'rose' }}-400 font-mono uppercase"
                                   placeholder="{{ __('booking.referral_code_placeholder') }}"
                                   maxlength="20">
                        </div>
                        @endif
                    </div>

                    <div class="mt-6 flex justify-between">
                        <button type="button" @click="prevStep()" class="px-6 py-2.5 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            {{ __('common.back') }}
                        </button>
                        <button type="submit" :disabled="!customerName || !customerPhone"
                                :class="customerName && customerPhone ? '{{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }}' : 'bg-gray-300 cursor-not-allowed'"
                                class="px-6 py-2.5 text-white font-medium rounded-lg transition">
                            {{ __('booking.confirm_booking') }}
                            <svg class="inline w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Check Status Link -->
        <div class="mt-6 text-center">
            <p class="text-gray-600">
                {{ __('booking.already_booked') }}
                <a href="{{ route('booking.status') }}" class="{{ $tc->link ?? 'text-rose-500 hover:text-rose-600' }}">{{ __('booking.check_status') }}</a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function bookingForm() {
    return {
        step: 1,
        openCategories: [{{ $categories->first()?->id ?? 0 }}],

        // Step 1
        serviceId: '',
        serviceName: '',
        serviceDuration: 0,
        servicePrice: 0,

        // Step 2
        appointmentDate: '',
        staffId: '',
        startTime: '',
        slots: [],
        morningSlots: [],
        afternoonSlots: [],
        loadingSlots: false,

        // Step 3 - Pre-fill with logged in customer data
        customerName: '{{ $loggedInCustomer?->name ?? '' }}',
        customerPhone: '{{ $loggedInCustomer?->phone ?? '' }}',
        customerEmail: '{{ $loggedInCustomer?->email ?? '' }}',
        notes: '',
        referralCode: '{{ request('ref', '') }}',
        isLoggedIn: {{ $loggedInCustomer ? 'true' : 'false' }},

        toggleCategory(id) {
            if (this.openCategories.includes(id)) {
                this.openCategories = this.openCategories.filter(c => c !== id);
            } else {
                this.openCategories.push(id);
            }
        },

        selectService(id, name, duration, price) {
            this.serviceId = id;
            this.serviceName = name;
            this.serviceDuration = duration;
            this.servicePrice = price;
        },

        async fetchSlots() {
            if (!this.appointmentDate) return;

            this.loadingSlots = true;
            this.slots = [];
            this.morningSlots = [];
            this.afternoonSlots = [];
            this.startTime = '';

            try {
                const params = new URLSearchParams({
                    date: this.appointmentDate,
                    service_id: this.serviceId,
                });
                if (this.staffId) {
                    params.append('staff_id', this.staffId);
                }

                const response = await fetch(`{{ route('booking.slots') }}?${params}`);
                const data = await response.json();

                this.slots = data.slots || [];
                this.morningSlots = data.morning || [];
                this.afternoonSlots = data.afternoon || [];
            } catch (error) {
                console.error('Error fetching slots:', error);
            } finally {
                this.loadingSlots = false;
            }
        },

        formatDate(dateStr) {
            if (!dateStr) return '';
            const date = new Date(dateStr);
            return date.toLocaleDateString('{{ app()->getLocale() === 'id' ? 'id-ID' : 'en-US' }}', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        },

        nextStep() {
            if (this.step === 1 && this.serviceId) {
                this.step = 2;
            } else if (this.step === 2 && this.startTime) {
                this.step = 3;
            }
        },

        prevStep() {
            if (this.step > 1) this.step--;
        },

        validateForm(e) {
            if (!this.serviceId || !this.appointmentDate || !this.startTime || !this.customerName || !this.customerPhone) {
                e.preventDefault();
                alert('{{ __('booking.fill_all_required') }}');
            }
        }
    }
}
</script>
@endpush
