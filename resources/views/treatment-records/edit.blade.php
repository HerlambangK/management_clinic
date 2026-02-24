@extends('layouts.dashboard')

@section('title', __('treatment.edit'))
@section('page-title', __('treatment.edit'))

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('treatment-records.show', $treatmentRecord) }}" class="inline-flex items-center text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 mb-6 max-sm:mb-4">
        <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        {{ __('treatment.back_to_detail') }}
    </a>

    <!-- Appointment Info (Read Only) -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4 mb-6 max-sm:mb-4">
        <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('treatment.appointment') }}</h3>
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-rose-100 dark:bg-rose-900/50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $treatmentRecord->customer->name }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $treatmentRecord->appointment->service->name }}</p>
                    <p class="text-sm text-rose-600 dark:text-rose-400">{{ format_date($treatmentRecord->appointment->appointment_date) }}</p>
                </div>
            </div>
        </div>

        <!-- Items from Invoice -->
        @if($treatmentRecord->has_transaction_items)
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('treatment.items_from_invoice') }}</h4>
                    @if($treatmentRecord->appointment->transaction)
                        <a href="{{ route('transactions.show', $treatmentRecord->appointment->transaction) }}" class="text-xs {{ $tc->link ?? 'text-rose-500 hover:text-rose-600' }}">
                            {{ $treatmentRecord->appointment->transaction->invoice_number }}
                        </a>
                    @endif
                </div>
                <div class="space-y-2">
                    @foreach($treatmentRecord->transaction_items as $item)
                        <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-600">
                            <div class="flex items-center gap-3">
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full
                                    @if($item->item_type === 'service') bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300
                                    @elseif($item->item_type === 'product') bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300
                                    @elseif($item->item_type === 'package') bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300
                                    @else bg-gray-100 text-gray-700 dark:bg-gray-600 dark:text-gray-300
                                    @endif">
                                    {{ ucfirst($item->item_type) }}
                                </span>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item->item_name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->quantity }}x @ {{ format_currency($item->unit_price) }}</p>
                                </div>
                            </div>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ format_currency($item->subtotal) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <form action="{{ route('treatment-records.update', $treatmentRecord) }}" method="POST" enctype="multipart/form-data" class="space-y-6 max-sm:space-y-4">
        @csrf
        @method('PUT')

        <!-- Treatment Notes -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">{{ __('treatment.notes') }}</h3>

            <div>
                <label for="notes" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('common.notes') }}</label>
                <textarea
                    id="notes"
                    name="notes"
                    rows="4"
                    class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 dark:focus:border-rose-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('notes') border-red-400 dark:border-red-500 @enderror"
                    placeholder="{{ __('treatment.notes_placeholder') }}"
                >{{ old('notes', $treatmentRecord->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Recommendations -->
            <div class="mt-4">
                <label for="recommendations" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('treatment.recommendations') }}</label>
                <textarea
                    id="recommendations"
                    name="recommendations"
                    rows="3"
                    class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 dark:focus:border-rose-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('recommendations') border-red-400 dark:border-red-500 @enderror"
                    placeholder="{{ __('treatment.recommendations_placeholder') }}"
                >{{ old('recommendations', $treatmentRecord->recommendations) }}</textarea>
                @error('recommendations')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Follow Up Date -->
            <div class="mt-4">
                <label for="follow_up_date" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('treatment.follow_up_date') }}</label>
                <input
                    type="date"
                    id="follow_up_date"
                    name="follow_up_date"
                    value="{{ old('follow_up_date', $treatmentRecord->follow_up_date?->format('Y-m-d')) }}"
                    min="{{ now()->addDay()->format('Y-m-d') }}"
                    class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 dark:focus:border-rose-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('follow_up_date') border-red-400 dark:border-red-500 @enderror"
                >
                @error('follow_up_date')
                    <p class="mt-1 text-sm max-sm:text-xs text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Photos -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-rose-500 to-pink-500 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('treatment.before_after_photos') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('treatment.photos_description') }}</p>
                </div>
            </div>

            <div class="grid lg:grid-cols-2 gap-6">
                <!-- Before Photos -->
                <div x-data="photoUploaderEdit('before', {{ json_encode($treatmentRecord->before_photo_urls ?? []) }}, {{ json_encode($treatmentRecord->before_photos ?? []) }})" class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <span class="w-6 h-6 bg-amber-100 dark:bg-amber-900/50 rounded-full flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            {{ __('treatment.before_photo') }}
                        </label>
                        <span class="text-xs text-gray-400" x-text="getTotalCount() + '/5 {{ __('treatment.photos') }}'"></span>
                    </div>

                    <!-- Existing Photos -->
                    <div class="grid grid-cols-3 gap-3" x-show="existingPhotos.length > 0">
                        <template x-for="(photo, index) in existingPhotos" :key="'existing-' + index">
                            <div class="relative group aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 shadow-sm ring-2 ring-amber-200 dark:ring-amber-800">
                                <img :src="photo.url" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <button type="button" @click.stop="removeExistingPhoto(index)" class="p-2 bg-red-500 rounded-full text-white hover:bg-red-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="absolute top-1 left-1 bg-amber-500 text-white text-xs px-1.5 py-0.5 rounded">{{ __('common.existing') }}</div>
                            </div>
                        </template>
                    </div>

                    <!-- Upload Area -->
                    <div
                        @click="$refs.fileInput.click()"
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="handleDrop($event)"
                        :class="isDragging ? 'border-rose-400 bg-rose-50 dark:bg-rose-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-rose-300 dark:hover:border-rose-500'"
                        class="relative border-2 border-dashed rounded-xl p-6 text-center cursor-pointer transition-all duration-200"
                        x-show="getTotalCount() < 5"
                    >
                        <input
                            type="file"
                            x-ref="fileInput"
                            @change="handleFiles($event)"
                            accept="image/jpeg,image/png,image/jpg,image/webp"
                            multiple
                            class="hidden"
                        >
                        <div class="space-y-2">
                            <div class="w-12 h-12 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('treatment.drag_drop_photos') }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('treatment.or_click_to_browse') }}</p>
                            </div>
                            <p class="text-xs text-gray-400">JPG, PNG, WebP. {{ __('treatment.max_5mb') }}</p>
                        </div>
                    </div>

                    <!-- New Photo Previews -->
                    <div class="grid grid-cols-3 gap-3" x-show="newPhotos.length > 0">
                        <template x-for="(photo, index) in newPhotos" :key="'new-' + index">
                            <div class="relative group aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 shadow-sm ring-2 ring-green-200 dark:ring-green-800">
                                <img :src="photo.preview" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <button type="button" @click.stop="removeNewPhoto(index)" class="p-2 bg-red-500 rounded-full text-white hover:bg-red-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="absolute top-1 left-1 bg-green-500 text-white text-xs px-1.5 py-0.5 rounded">{{ __('common.new') }}</div>
                            </div>
                        </template>
                    </div>

                    <!-- Hidden inputs for keeping existing photos -->
                    <template x-for="(photo, index) in existingPhotos" :key="'keep-before-' + index">
                        <input type="hidden" name="keep_before_photos[]" :value="photo.path">
                    </template>

                    @error('before_photos')
                        <p class="text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    @error('before_photos.*')
                        <p class="text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- After Photos -->
                <div x-data="photoUploaderEdit('after', {{ json_encode($treatmentRecord->after_photo_urls ?? []) }}, {{ json_encode($treatmentRecord->after_photos ?? []) }})" class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <span class="w-6 h-6 bg-green-100 dark:bg-green-900/50 rounded-full flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            {{ __('treatment.after_photo') }}
                        </label>
                        <span class="text-xs text-gray-400" x-text="getTotalCount() + '/5 {{ __('treatment.photos') }}'"></span>
                    </div>

                    <!-- Existing Photos -->
                    <div class="grid grid-cols-3 gap-3" x-show="existingPhotos.length > 0">
                        <template x-for="(photo, index) in existingPhotos" :key="'existing-' + index">
                            <div class="relative group aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 shadow-sm ring-2 ring-green-200 dark:ring-green-800">
                                <img :src="photo.url" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <button type="button" @click.stop="removeExistingPhoto(index)" class="p-2 bg-red-500 rounded-full text-white hover:bg-red-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="absolute top-1 left-1 bg-green-500 text-white text-xs px-1.5 py-0.5 rounded">{{ __('common.existing') }}</div>
                            </div>
                        </template>
                    </div>

                    <!-- Upload Area -->
                    <div
                        @click="$refs.fileInput.click()"
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="handleDrop($event)"
                        :class="isDragging ? 'border-rose-400 bg-rose-50 dark:bg-rose-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-rose-300 dark:hover:border-rose-500'"
                        class="relative border-2 border-dashed rounded-xl p-6 text-center cursor-pointer transition-all duration-200"
                        x-show="getTotalCount() < 5"
                    >
                        <input
                            type="file"
                            x-ref="fileInput"
                            @change="handleFiles($event)"
                            accept="image/jpeg,image/png,image/jpg,image/webp"
                            multiple
                            class="hidden"
                        >
                        <div class="space-y-2">
                            <div class="w-12 h-12 mx-auto bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('treatment.drag_drop_photos') }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('treatment.or_click_to_browse') }}</p>
                            </div>
                            <p class="text-xs text-gray-400">JPG, PNG, WebP. {{ __('treatment.max_5mb') }}</p>
                        </div>
                    </div>

                    <!-- New Photo Previews -->
                    <div class="grid grid-cols-3 gap-3" x-show="newPhotos.length > 0">
                        <template x-for="(photo, index) in newPhotos" :key="'new-' + index">
                            <div class="relative group aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 shadow-sm ring-2 ring-blue-200 dark:ring-blue-800">
                                <img :src="photo.preview" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <button type="button" @click.stop="removeNewPhoto(index)" class="p-2 bg-red-500 rounded-full text-white hover:bg-red-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="absolute top-1 left-1 bg-blue-500 text-white text-xs px-1.5 py-0.5 rounded">{{ __('common.new') }}</div>
                            </div>
                        </template>
                    </div>

                    <!-- Hidden inputs for keeping existing photos -->
                    <template x-for="(photo, index) in existingPhotos" :key="'keep-after-' + index">
                        <input type="hidden" name="keep_after_photos[]" :value="photo.path">
                    </template>

                    @error('after_photos')
                        <p class="text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    @error('after_photos.*')
                        <p class="text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tips -->
            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                <h4 class="text-sm font-medium text-blue-700 dark:text-blue-300 mb-2">{{ __('treatment.photo_tips_title') }}</h4>
                <ul class="text-xs text-blue-600 dark:text-blue-400 space-y-1">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('treatment.photo_tip_1') }}
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('treatment.photo_tip_2') }}
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('treatment.photo_tip_3') }}
                    </li>
                </ul>
            </div>
        </div>

        @push('scripts')
        <script>
            function photoUploaderEdit(type, existingUrls, existingPaths) {
                return {
                    existingPhotos: existingUrls.map((url, index) => ({
                        url: url,
                        path: existingPaths[index] || ''
                    })),
                    newPhotos: [],
                    isDragging: false,
                    maxPhotos: 5,
                    maxSize: 5 * 1024 * 1024, // 5MB

                    getTotalCount() {
                        return this.existingPhotos.length + this.newPhotos.length;
                    },

                    handleFiles(event) {
                        const files = event.target.files;
                        this.addFiles(files);
                    },

                    handleDrop(event) {
                        this.isDragging = false;
                        const files = event.dataTransfer.files;
                        this.addFiles(files);
                    },

                    addFiles(files) {
                        for (let i = 0; i < files.length; i++) {
                            if (this.getTotalCount() >= this.maxPhotos) {
                                alert('{{ __("treatment.max_photos_reached") }}');
                                break;
                            }

                            const file = files[i];

                            // Validate file type
                            if (!file.type.match(/^image\/(jpeg|png|jpg|webp)$/)) {
                                alert('{{ __("treatment.invalid_file_type") }}');
                                continue;
                            }

                            // Validate file size
                            if (file.size > this.maxSize) {
                                alert('{{ __("treatment.file_too_large") }}');
                                continue;
                            }

                            // Create preview
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.newPhotos.push({
                                    file: file,
                                    preview: e.target.result
                                });
                                this.updateFormInputs();
                            };
                            reader.readAsDataURL(file);
                        }
                    },

                    removeExistingPhoto(index) {
                        if (confirm('{{ __("treatment.delete_photo_confirm") }}')) {
                            this.existingPhotos.splice(index, 1);
                        }
                    },

                    removeNewPhoto(index) {
                        this.newPhotos.splice(index, 1);
                        this.updateFormInputs();
                    },

                    updateFormInputs() {
                        // Create a new DataTransfer to hold files
                        const dataTransfer = new DataTransfer();
                        this.newPhotos.forEach(photo => {
                            dataTransfer.items.add(photo.file);
                        });

                        // Find or create the hidden file input
                        let input = document.querySelector(`input[name="${type}_photos[]"]`);
                        if (!input) {
                            input = document.createElement('input');
                            input.type = 'file';
                            input.name = `${type}_photos[]`;
                            input.multiple = true;
                            input.style.display = 'none';
                            this.$el.appendChild(input);
                        }
                        input.files = dataTransfer.files;
                    }
                }
            }
        </script>
        @endpush

        <!-- Submit -->
        <div class="flex flex-row max-sm:flex-col items-center gap-3 max-sm:gap-2">
            <button type="submit" class="px-6 py-2.5 max-sm:w-full max-sm:py-2 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm font-medium rounded-lg transition">
                {{ __('common.save_changes') }}
            </button>
            <a href="{{ route('treatment-records.show', $treatmentRecord) }}" class="px-6 py-2.5 max-sm:w-full max-sm:py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                {{ __('common.cancel') }}
            </a>
        </div>
    </form>
</div>
@endsection
