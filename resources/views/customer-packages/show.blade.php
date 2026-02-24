@extends('layouts.dashboard')

@section('title', __('customer_package.detail'))
@section('page-title', __('customer_package.detail'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6 max-sm:space-y-4">
    <!-- Back Button & Actions -->
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4 max-sm:gap-3">
        <a href="{{ route('customers.show', $customerPackage->customer) }}" class="inline-flex items-center text-sm max-sm:text-xs text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('customer_package.back_to_profile') }}
        </a>
        @if($customerPackage->is_usable)
            <button
                type="button"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 max-sm:w-full {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm max-sm:text-xs font-medium rounded-lg transition"
                x-data
                @click="$dispatch('open-use-session-modal')"
            >
                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ __('customer_package.use_session') }}
            </button>
        @endif
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Package Info -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <div class="flex items-start justify-between mb-4 max-sm:mb-3">
            <div>
                <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('customer_package.customer') }}</p>
                <a href="{{ route('customers.show', $customerPackage->customer) }}" class="text-xl max-sm:text-lg font-bold text-gray-900 dark:text-white hover:text-rose-600 dark:hover:text-rose-400">
                    {{ $customerPackage->customer->name }}
                </a>
            </div>
            @php
                $statusColors = [
                    'active' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400',
                    'completed' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400',
                    'expired' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400',
                    'cancelled' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                ];
            @endphp
            <span class="inline-flex items-center px-3 py-1 max-sm:px-2 max-sm:py-0.5 rounded-full text-sm max-sm:text-xs font-medium {{ $statusColors[$customerPackage->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                {{ $customerPackage->status_label }}
            </span>
        </div>

        <div class="bg-rose-50 dark:bg-rose-900/20 rounded-lg p-4 max-sm:p-3 mb-6 max-sm:mb-4">
            <div class="flex items-center gap-4 max-sm:gap-3">
                <div class="w-14 h-14 max-sm:w-10 max-sm:h-10 bg-rose-100 dark:bg-rose-900/50 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 max-sm:w-5 max-sm:h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div>
                    <a href="{{ route('packages.show', $customerPackage->package) }}" class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-white hover:text-rose-600 dark:hover:text-rose-400">
                        {{ $customerPackage->package->name }}
                    </a>
                    @if($customerPackage->package->service)
                        <p class="text-sm max-sm:text-xs text-gray-600 dark:text-gray-400">{{ $customerPackage->package->service->name }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Progress -->
        <div class="mb-6 max-sm:mb-4">
            <div class="flex items-center justify-between mb-2 max-sm:mb-1.5">
                <span class="text-sm max-sm:text-xs text-gray-600 dark:text-gray-400">{{ __('customer_package.sessions_progress') }}</span>
                <span class="text-sm max-sm:text-xs font-medium text-gray-900 dark:text-white">{{ $customerPackage->sessions_used }}/{{ $customerPackage->sessions_total }} {{ __('customer_package.sessions') }}</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-3 max-sm:h-2.5">
                <div class="{{ $tc->accentBg ?? 'bg-rose-500' }} h-3 max-sm:h-2.5 rounded-full transition-all" style="width: {{ $customerPackage->usage_percentage }}%"></div>
            </div>
            <div class="flex items-center justify-between mt-2 max-sm:mt-1.5 text-sm max-sm:text-xs">
                <span class="text-gray-500 dark:text-gray-400">{{ __('customer_package.used_label') }}: {{ $customerPackage->sessions_used }}</span>
                <span class="{{ $tc->accent ?? 'text-rose-600' }} font-medium">{{ __('customer_package.remaining_label') }}: {{ $customerPackage->sessions_remaining }}</span>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid sm:grid-cols-2 gap-4 max-sm:gap-3">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 max-sm:p-3">
                <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Tanggal Pembelian</p>
                <p class="font-medium text-gray-900 dark:text-white max-sm:text-sm">{{ format_date($customerPackage->purchased_at) }}</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 max-sm:p-3">
                <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Berlaku Sampai</p>
                <p class="font-medium text-gray-900 dark:text-white max-sm:text-sm">{{ format_date($customerPackage->expires_at) }}</p>
                @if($customerPackage->status === 'active')
                    @if($customerPackage->days_remaining <= 30)
                        <p class="text-xs text-yellow-500 dark:text-yellow-400 mt-1">{{ $customerPackage->days_remaining }} hari lagi</p>
                    @else
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $customerPackage->days_remaining }} hari lagi</p>
                    @endif
                @endif
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 max-sm:p-3">
                <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Harga yang Dibayar</p>
                <p class="font-medium text-gray-900 dark:text-white max-sm:text-sm">{{ $customerPackage->formatted_price_paid }}</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 max-sm:p-3">
                <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Dijual Oleh</p>
                <p class="font-medium text-gray-900 dark:text-white max-sm:text-sm">{{ $customerPackage->seller?->name ?? '-' }}</p>
            </div>
        </div>

        @if($customerPackage->notes)
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 mb-1">Catatan</p>
                <p class="text-gray-700 dark:text-gray-300 max-sm:text-sm">{{ $customerPackage->notes }}</p>
            </div>
        @endif

        <!-- Cancel Button -->
        @if($customerPackage->status === 'active')
            <div class="mt-6 max-sm:mt-4 pt-6 max-sm:pt-4 border-t border-gray-100 dark:border-gray-700">
                <form action="{{ route('customer-packages.cancel', $customerPackage) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin membatalkan paket ini?')">
                    @csrf
                    <button type="submit" class="text-sm max-sm:text-xs text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                        Batalkan Paket
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Usage History -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-white mb-4 max-sm:mb-3">Riwayat Penggunaan</h3>

        @if($customerPackage->usages->count() > 0)
            <div class="space-y-3 max-sm:space-y-2">
                @foreach($customerPackage->usages as $index => $usage)
                    <div class="flex items-start gap-4 max-sm:gap-3 p-4 max-sm:p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="w-10 h-10 max-sm:w-8 max-sm:h-8 bg-rose-100 dark:bg-rose-900/50 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-sm max-sm:text-xs font-medium text-rose-600 dark:text-rose-400">{{ $index + 1 }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="font-medium text-gray-900 dark:text-white max-sm:text-sm">Sesi ke-{{ $index + 1 }}</p>
                                <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ format_date($usage->used_at) }}</p>
                            </div>
                            @if($usage->appointment)
                                <p class="text-sm max-sm:text-xs text-gray-600 dark:text-gray-400 mt-1">
                                    Appointment: {{ $usage->appointment->service->name }}
                                </p>
                            @endif
                            @if($usage->notes)
                                <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $usage->notes }}</p>
                            @endif
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Oleh: {{ $usage->usedByStaff?->name ?? '-' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 max-sm:py-6">
                <svg class="mx-auto h-12 w-12 max-sm:h-10 max-sm:w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="mt-2 text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Belum ada sesi yang digunakan</p>
            </div>
        @endif
    </div>
</div>

<!-- Use Session Modal -->
@if($customerPackage->is_usable)
    <div
        x-data="{ open: false }"
        @open-use-session-modal.window="open = true"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
    >
        <div class="flex items-center justify-center min-h-screen px-4 max-sm:px-3">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50" @click="open = false"></div>

            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full p-6 max-sm:p-4">
                <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-white mb-4 max-sm:mb-3">Gunakan Sesi Paket</h3>

                <form action="{{ route('customer-packages.use-session', $customerPackage) }}" method="POST">
                    @csrf

                    <div class="mb-4 max-sm:mb-3">
                        <p class="text-sm max-sm:text-xs text-gray-600 dark:text-gray-400 mb-2 max-sm:mb-1.5">
                            Sesi yang tersisa: <span class="font-medium text-rose-600 dark:text-rose-400">{{ $customerPackage->sessions_remaining }}</span>
                        </p>
                    </div>

                    <div class="mb-4 max-sm:mb-3">
                        <label for="notes" class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">Catatan (Opsional)</label>
                        <textarea
                            id="notes"
                            name="notes"
                            rows="2"
                            class="w-full px-4 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                            placeholder="Catatan penggunaan sesi..."
                        ></textarea>
                    </div>

                    <div class="flex flex-row max-sm:flex-col items-center justify-end gap-3">
                        <button type="button" @click="open = false" class="px-4 py-2 max-sm:w-full max-sm:py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 max-sm:w-full max-sm:py-1.5 {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm max-sm:text-xs font-medium rounded-lg transition">
                            Gunakan 1 Sesi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection
