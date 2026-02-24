@extends('layouts.dashboard')

@section('title', 'Detail Catatan Treatment')
@section('page-title', 'Detail Catatan Treatment')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 max-sm:space-y-4">
    <!-- Back Button & Actions -->
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4 max-sm:gap-3">
        <a href="{{ route('customers.show', $treatmentRecord->customer) }}" class="inline-flex items-center text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
            <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Profil Customer
        </a>
        <div class="flex items-center gap-2 max-sm:w-full max-sm:flex-wrap max-sm:justify-between">
            <a href="{{ route('treatment-records.pdf', $treatmentRecord) }}" class="inline-flex items-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                {{ __('treatment.export_pdf') }}
            </a>
            <a href="{{ route('treatment-records.edit', $treatmentRecord) }}" class="inline-flex items-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                {{ __('common.edit') }}
            </a>
            <form action="{{ route('treatment-records.destroy', $treatmentRecord) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('treatment.delete_confirm') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 bg-white dark:bg-gray-800 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-red-50 dark:hover:bg-red-900/50 transition">
                    <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    {{ __('common.delete') }}
                </button>
            </form>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Appointment Info -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <div class="flex items-center gap-4 mb-4 max-sm:mb-3">
            <div class="w-12 h-12 bg-rose-100 dark:bg-rose-900/50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Appointment</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">{{ format_date($treatmentRecord->appointment->appointment_date) }}</p>
            </div>
        </div>

        <div class="grid sm:grid-cols-3 gap-4 max-sm:gap-3">
            <div>
                <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('treatment.customer') }}</p>
                <a href="{{ route('customers.show', $treatmentRecord->customer) }}" class="font-medium text-sm max-sm:text-xs text-gray-900 dark:text-gray-100 hover:text-rose-600 dark:hover:text-rose-400">
                    {{ $treatmentRecord->customer->name }}
                </a>
            </div>
            <div>
                <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('treatment.service') }}</p>
                <p class="font-medium text-sm max-sm:text-xs text-gray-900 dark:text-gray-100">{{ $treatmentRecord->appointment->service->name }}</p>
            </div>
            <div>
                <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('treatment.beautician') }}</p>
                <p class="font-medium text-sm max-sm:text-xs text-gray-900 dark:text-gray-100">{{ $treatmentRecord->staff->name }}</p>
            </div>
        </div>
    </div>

    <!-- Treatment Notes -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">Catatan Treatment</h3>

        @if($treatmentRecord->notes)
            <div class="prose prose-sm max-w-none">
                <p class="text-sm max-sm:text-xs text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $treatmentRecord->notes }}</p>
            </div>
        @else
            <p class="text-sm max-sm:text-xs text-gray-400 dark:text-gray-500 italic">Tidak ada catatan</p>
        @endif

        <!-- Transaction Items (from Invoice) -->
        @if($treatmentRecord->has_transaction_items)
            <div class="mt-6 max-sm:mt-4 pt-6 max-sm:pt-4 border-t border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300">{{ __('treatment.items_from_invoice') }}</h4>
                    @if($treatmentRecord->appointment->transaction)
                        <a href="{{ route('transactions.show', $treatmentRecord->appointment->transaction) }}" class="text-xs {{ $tc->link ?? 'text-rose-500 hover:text-rose-600' }}">
                            {{ $treatmentRecord->appointment->transaction->invoice_number }}
                        </a>
                    @endif
                </div>
                <div class="space-y-2">
                    @foreach($treatmentRecord->transaction_items as $item)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                    @if($item->item_type === 'service') bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400
                                    @elseif($item->item_type === 'product') bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400
                                    @elseif($item->item_type === 'package') bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-400
                                    @else bg-gray-100 text-gray-700 dark:bg-gray-600 dark:text-gray-300
                                    @endif">
                                    {{ $item->item_type_label }}
                                </span>
                                <span class="text-sm text-gray-900 dark:text-gray-100">{{ $item->item_name }}</span>
                                @if($item->quantity > 1)
                                    <span class="text-xs text-gray-500 dark:text-gray-400">x{{ $item->quantity }}</span>
                                @endif
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $item->formatted_total_price }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Recommendations -->
        @if($treatmentRecord->recommendations)
            <div class="mt-6 max-sm:mt-4 pt-6 max-sm:pt-4 border-t border-gray-100 dark:border-gray-700">
                <h4 class="text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">Rekomendasi Perawatan</h4>
                <div class="bg-blue-50 dark:bg-blue-900/50 rounded-lg p-4 max-sm:p-3">
                    <p class="text-sm max-sm:text-xs text-blue-800 dark:text-blue-400 whitespace-pre-line">{{ $treatmentRecord->recommendations }}</p>
                </div>
            </div>
        @endif

        <!-- Follow Up Date -->
        @if($treatmentRecord->follow_up_date)
            <div class="mt-6 max-sm:mt-4 pt-6 max-sm:pt-4 border-t border-gray-100 dark:border-gray-700">
                <h4 class="text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">Tanggal Follow Up</h4>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 max-sm:w-4 max-sm:h-4 text-rose-500 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="font-medium text-sm max-sm:text-xs text-gray-900 dark:text-gray-100">{{ format_date($treatmentRecord->follow_up_date) }}</span>
                    @if($treatmentRecord->follow_up_date->isPast())
                        <span class="text-xs text-red-500 dark:text-red-400">(Sudah lewat)</span>
                    @elseif($treatmentRecord->follow_up_date->isToday())
                        <span class="text-xs text-yellow-500 dark:text-yellow-400">(Hari ini)</span>
                    @else
                        <span class="text-xs text-gray-500 dark:text-gray-400">({{ $treatmentRecord->follow_up_date->diffForHumans() }})</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Photos -->
    @if($treatmentRecord->has_photos)
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
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-6 h-6 bg-amber-100 dark:bg-amber-900/50 rounded-full flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('treatment.before_photo') }}</p>
                        <span class="text-xs text-gray-400">({{ count($treatmentRecord->before_photos ?? []) }} {{ __('treatment.photos') }})</span>
                    </div>
                    @if(!empty($treatmentRecord->before_photos))
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($treatmentRecord->before_photo_urls as $index => $url)
                                <a href="{{ $url }}" target="_blank" class="block relative group aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 shadow-sm hover:shadow-md transition">
                                    <img src="{{ $url }}" alt="Before {{ $index + 1 }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                        </svg>
                                    </div>
                                    <div class="absolute bottom-1 right-1 bg-black/60 text-white text-xs px-1.5 py-0.5 rounded">{{ $index + 1 }}</div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-600 p-8 text-center">
                            <svg class="w-10 h-10 mx-auto text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm text-gray-400 dark:text-gray-500">{{ __('treatment.no_photo') }}</p>
                        </div>
                    @endif
                </div>

                <!-- After Photos -->
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-6 h-6 bg-green-100 dark:bg-green-900/50 rounded-full flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('treatment.after_photo') }}</p>
                        <span class="text-xs text-gray-400">({{ count($treatmentRecord->after_photos ?? []) }} {{ __('treatment.photos') }})</span>
                    </div>
                    @if(!empty($treatmentRecord->after_photos))
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($treatmentRecord->after_photo_urls as $index => $url)
                                <a href="{{ $url }}" target="_blank" class="block relative group aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 shadow-sm hover:shadow-md transition">
                                    <img src="{{ $url }}" alt="After {{ $index + 1 }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                        </svg>
                                    </div>
                                    <div class="absolute bottom-1 right-1 bg-black/60 text-white text-xs px-1.5 py-0.5 rounded">{{ $index + 1 }}</div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-600 p-8 text-center">
                            <svg class="w-10 h-10 mx-auto text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm text-gray-400 dark:text-gray-500">{{ __('treatment.no_photo') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Timestamps -->
    <div class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">
        <p>Dibuat: {{ format_datetime($treatmentRecord->created_at) }}</p>
        @if($treatmentRecord->updated_at != $treatmentRecord->created_at)
            <p>Diperbarui: {{ format_datetime($treatmentRecord->updated_at) }}</p>
        @endif
    </div>
</div>
@endsection
