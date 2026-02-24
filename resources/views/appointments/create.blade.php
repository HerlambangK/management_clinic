@extends('layouts.dashboard')

@section('title', __('appointment.new_booking'))
@section('page-title', __('appointment.new_booking'))

@section('content')
<div class="max-w-4xl mx-auto" x-data="appointmentForm()">
    <!-- Back Button -->
    <a href="{{ route('appointments.index') }}" class="inline-flex items-center text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 mb-6 max-sm:mb-4">
        <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        {{ __('common.back') }}
    </a>

    <form action="{{ route('appointments.store') }}" method="POST" class="space-y-6 max-sm:space-y-4">
        @csrf

        <!-- Customer Selection -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('appointment.step_customer') }}</h3>

            <div>
                <label for="customer_id" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('appointment.customer') }} <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select
                        id="customer_id"
                        name="customer_id"
                        x-model="customerId"
                        class="w-full pl-4 pr-12 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition appearance-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('customer_id') border-red-400 @enderror"
                        required
                    >
                        <option value="">{{ __('appointment.select_service') }}</option>
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
                <p class="mt-2 text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">
                    {{ __('appointment.customer_not_registered') }}
                    <a href="{{ route('customers.create') }}" class="{{ $tc->link ?? 'text-rose-500 hover:text-rose-600' }} font-medium">{{ __('appointment.add_new_customer') }}</a>
                </p>
            </div>
        </div>

        <!-- Service Selection -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('appointment.step_service') }}</h3>

            <div>
                <label for="service_id" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('appointment.service') }} <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select
                        id="service_id"
                        name="service_id"
                        x-model="serviceId"
                        @change="fetchSlots()"
                        class="w-full pl-4 pr-12 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition appearance-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('service_id') border-red-400 @enderror"
                        required
                    >
                        <option value="">{{ __('appointment.select_service') }}</option>
                        @foreach($categories as $category)
                            @if($category->services->count() > 0)
                                <optgroup label="{{ $category->name }}">
                                    @foreach($category->services as $service)
                                        <option
                                            value="{{ $service->id }}"
                                            data-duration="{{ $service->duration_minutes }}"
                                            data-price="{{ $service->price }}"
                                            {{ old('service_id') == $service->id ? 'selected' : '' }}
                                        >
                                            {{ $service->name }} ({{ $service->duration_minutes }} {{ __('common.minutes') }} - {{ format_currency($service->price) }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                @error('service_id')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Date & Time Selection -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('appointment.step_datetime') }}</h3>

            <div class="grid grid-cols-2 max-sm:grid-cols-1 gap-4 max-sm:gap-3">
                <!-- Date -->
                <div>
                    <label for="appointment_date" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('appointment.date') }} <span class="text-red-500">*</span></label>
                    <input
                        type="date"
                        id="appointment_date"
                        name="appointment_date"
                        x-model="appointmentDate"
                        @change="fetchSlots()"
                        min="{{ today()->format('Y-m-d') }}"
                        class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('appointment_date') border-red-400 @enderror"
                        required
                    >
                    @error('appointment_date')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Staff -->
                <div>
                    <label for="staff_id" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ $staffLabel }}</label>
                    <div class="relative">
                        <select
                            id="staff_id"
                            name="staff_id"
                            x-model="staffId"
                            @change="fetchSlots()"
                            class="w-full pl-4 pr-12 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition appearance-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('staff_id') border-red-400 @enderror"
                        >
                            <option value="">{{ __('appointment.anyone_available') }}</option>
                            @foreach($beauticians as $beautician)
                                <option value="{{ $beautician->id }}" {{ old('staff_id') == $beautician->id ? 'selected' : '' }}>
                                    {{ $beautician->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    @error('staff_id')
                        <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Time Slots -->
            <div class="mt-4 max-sm:mt-3">
                <label class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('appointment.time') }} <span class="text-red-500">*</span></label>

                <div x-show="!serviceId || !appointmentDate" class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 italic">
                    {{ __('appointment.select_service_date') }}
                </div>

                <div x-show="loading" class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">
                    <svg class="animate-spin h-5 w-5 max-sm:h-4 max-sm:w-4 text-rose-500 inline mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ __('appointment.loading_slots') }}
                </div>

                <div x-show="serviceId && appointmentDate && !loading && slots.length === 0" class="text-sm max-sm:text-xs text-red-500">
                    {{ __('appointment.no_slots_date') }}
                </div>

                <div x-show="serviceId && appointmentDate && !loading && slots.length > 0" class="grid grid-cols-6 max-sm:grid-cols-4 gap-2">
                    <template x-for="slot in slots" :key="slot">
                        <label class="relative">
                            <input
                                type="radio"
                                name="start_time"
                                :value="slot"
                                x-model="startTime"
                                class="peer sr-only"
                                required
                            >
                            <div class="px-3 py-2 max-sm:px-2 max-sm:py-1.5 text-center text-sm max-sm:text-xs border border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer transition peer-checked:border-rose-500 peer-checked:bg-rose-50 dark:peer-checked:bg-rose-900/30 peer-checked:text-rose-600 dark:peer-checked:text-rose-400 hover:border-rose-200 dark:hover:border-rose-700 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <span x-text="slot"></span>
                            </div>
                        </label>
                    </template>
                </div>
                @error('start_time')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Additional Info -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">4. {{ __('appointment.additional_info') }}</h3>

            <!-- Source -->
            <div>
                <label for="source" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('appointment.booking_source') }}</label>
                <div class="relative">
                    <select
                        id="source"
                        name="source"
                        class="w-full pl-4 pr-12 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition appearance-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('source') border-red-400 @enderror"
                    >
                        <option value="walk_in" {{ old('source') === 'walk_in' ? 'selected' : '' }}>{{ __('appointment.walk_in') }}</option>
                        <option value="phone" {{ old('source') === 'phone' ? 'selected' : '' }}>{{ __('appointment.phone') }}</option>
                        <option value="whatsapp" {{ old('source') === 'whatsapp' ? 'selected' : '' }}>{{ __('appointment.whatsapp') }}</option>
                        <option value="online" {{ old('source') === 'online' ? 'selected' : '' }}>{{ __('appointment.online') }}</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                @error('source')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div class="mt-4 max-sm:mt-3">
                <label for="notes" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('appointment.notes') }}</label>
                <textarea
                    id="notes"
                    name="notes"
                    rows="3"
                    class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('notes') border-red-400 @enderror"
                    placeholder="{{ __('appointment.notes_placeholder') }}"
                >{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Submit -->
        <div class="flex flex-row max-sm:flex-col items-center gap-3 max-sm:gap-2">
            <button type="submit" class="px-6 py-2.5 max-sm:w-full max-sm:py-2 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm font-medium rounded-lg transition">
                {{ __('appointment.save_appointment') }}
            </button>
            <a href="{{ route('appointments.index') }}" class="px-6 py-2.5 max-sm:w-full max-sm:py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition text-center">
                {{ __('common.cancel') }}
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function appointmentForm() {
    return {
        customerId: '{{ old('customer_id', $selectedCustomerId) }}',
        serviceId: '{{ old('service_id') }}',
        staffId: '{{ old('staff_id') }}',
        appointmentDate: '{{ old('appointment_date', $selectedDate) }}',
        startTime: '{{ old('start_time') }}',
        slots: [],
        loading: false,

        async fetchSlots() {
            if (!this.serviceId || !this.appointmentDate) {
                this.slots = [];
                return;
            }

            this.loading = true;
            this.startTime = '';

            try {
                const params = new URLSearchParams({
                    service_id: this.serviceId,
                    date: this.appointmentDate,
                });

                if (this.staffId) {
                    params.append('staff_id', this.staffId);
                }

                const response = await fetch(`{{ route('appointments.slots') }}?${params}`);
                const data = await response.json();
                this.slots = data.slots || [];
            } catch (error) {
                console.error('Error fetching slots:', error);
                this.slots = [];
            } finally {
                this.loading = false;
            }
        },

        init() {
            if (this.serviceId && this.appointmentDate) {
                this.fetchSlots();
            }
        }
    }
}
</script>
@endpush
@endsection
