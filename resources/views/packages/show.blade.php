@extends('layouts.dashboard')

@section('title', $package->name)
@section('page-title', 'Detail Paket')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 max-sm:space-y-4">
    <!-- Back Button & Actions -->
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4 max-sm:gap-3">
        <a href="{{ route('packages.index') }}" class="inline-flex items-center text-sm max-sm:text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 mb-6 max-sm:mb-4">
            <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Paket
        </a>
        <div class="flex flex-row max-sm:flex-col items-center max-sm:w-full gap-2">
            <a href="{{ route('customer-packages.create', ['package_id' => $package->id]) }}" class="inline-flex items-center gap-2 px-4 py-2 max-sm:w-full max-sm:justify-center {{ $tc->button ?? 'bg-rose-500 hover:bg-rose-600' }} text-white text-sm font-medium rounded-lg transition">
                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Jual Paket
            </a>
            <a href="{{ route('packages.edit', $package) }}" class="inline-flex items-center gap-2 px-4 py-2 max-sm:w-full max-sm:justify-center bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
            <form action="{{ route('packages.destroy', $package) }}" method="POST" class="inline max-sm:w-full" onsubmit="return confirm('Yakin ingin menghapus paket ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 max-sm:w-full max-sm:justify-center bg-white dark:bg-gray-700 border border-red-200 dark:border-red-900/50 text-red-600 dark:text-red-400 text-sm font-medium rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                    <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-900/50 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm max-sm:text-xs">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-900/50 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg text-sm max-sm:text-xs">
            {{ session('error') }}
        </div>
    @endif

    <!-- Package Info -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <div class="flex items-start justify-between mb-4 max-sm:mb-3">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h2 class="text-2xl max-sm:text-lg font-bold text-gray-900 dark:text-white">{{ $package->name }}</h2>
                    @if(!$package->is_active)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">Tidak Aktif</span>
                    @endif
                </div>
                @if($package->service)
                    <p class="text-gray-600 dark:text-gray-400 text-sm max-sm:text-xs">{{ $package->service->name }}</p>
                @endif
            </div>
            @if($package->discount_percentage > 0)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm max-sm:text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-400">
                    Hemat {{ $package->discount_percentage }}%
                </span>
            @endif
        </div>

        @if($package->description)
            <p class="text-gray-600 dark:text-gray-400 text-sm max-sm:text-xs mb-6 max-sm:mb-4">{{ $package->description }}</p>
        @endif

        <div class="grid sm:grid-cols-4 gap-4 max-sm:gap-3">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-5 max-sm:p-3">
                <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Jumlah Sesi</p>
                <p class="text-2xl max-sm:text-lg font-bold text-gray-900 dark:text-white">{{ $package->total_sessions }}x</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-5 max-sm:p-3">
                <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Masa Berlaku</p>
                <p class="text-2xl max-sm:text-lg font-bold text-gray-900 dark:text-white">{{ $package->validity_days }} <span class="text-sm max-sm:text-xs font-normal">hari</span></p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-5 max-sm:p-3">
                <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Harga per Sesi</p>
                <p class="text-2xl max-sm:text-lg font-bold text-gray-900 dark:text-white">{{ $package->formatted_price_per_session }}</p>
            </div>
            <div class="bg-rose-50 dark:bg-rose-900/20 rounded-lg p-5 max-sm:p-3">
                <p class="text-sm max-sm:text-xs text-rose-600 dark:text-rose-400">Harga Paket</p>
                <p class="text-2xl max-sm:text-lg font-bold text-rose-600 dark:text-rose-400">{{ $package->formatted_package_price }}</p>
                @if($package->discount_percentage > 0)
                    <p class="text-sm max-sm:text-xs text-gray-400 line-through">{{ $package->formatted_original_price }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid sm:grid-cols-4 gap-4 max-sm:gap-3">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 max-sm:p-3">
            <div class="flex items-center gap-4 max-sm:gap-3">
                <div class="w-12 h-12 max-sm:w-10 max-sm:h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 max-sm:w-5 max-sm:h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl max-sm:text-lg font-bold text-gray-900 dark:text-white">{{ $stats['total_sold'] }}</p>
                    <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Total Terjual</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 max-sm:p-3">
            <div class="flex items-center gap-4 max-sm:gap-3">
                <div class="w-12 h-12 max-sm:w-10 max-sm:h-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 max-sm:w-5 max-sm:h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl max-sm:text-lg font-bold text-gray-900 dark:text-white">{{ $stats['active'] }}</p>
                    <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Aktif</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 max-sm:p-3">
            <div class="flex items-center gap-4 max-sm:gap-3">
                <div class="w-12 h-12 max-sm:w-10 max-sm:h-10 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 max-sm:w-5 max-sm:h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl max-sm:text-lg font-bold text-gray-900 dark:text-white">{{ $stats['completed'] }}</p>
                    <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Selesai</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 max-sm:p-3">
            <div class="flex items-center gap-4 max-sm:gap-3">
                <div class="w-12 h-12 max-sm:w-10 max-sm:h-10 bg-rose-100 dark:bg-rose-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 max-sm:w-5 max-sm:h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl max-sm:text-lg font-bold text-gray-900 dark:text-white">{{ format_currency($stats['total_revenue']) }}</p>
                    <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Total Pendapatan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Purchases -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
        <div class="flex items-center justify-between mb-4 max-sm:mb-3">
            <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-white">Pembelian Terbaru</h3>
            @if($package->customerPackages->count() > 0)
                <a href="{{ route('customer-packages.index', ['package_id' => $package->id]) }}" class="text-sm max-sm:text-xs text-rose-500 hover:text-rose-600 dark:text-rose-400 dark:hover:text-rose-300 font-medium">
                    Lihat Semua
                </a>
            @endif
        </div>

        @if($package->customerPackages->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 max-sm:px-2 py-3 max-sm:py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('customer.title') }}</th>
                            <th class="px-4 max-sm:px-2 py-3 max-sm:py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('common.date') }}</th>
                            <th class="px-4 max-sm:px-2 py-3 max-sm:py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('customer_package.sessions') }}</th>
                            <th class="px-4 max-sm:px-2 py-3 max-sm:py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('common.status') }}</th>
                            <th class="px-4 max-sm:px-2 py-3 max-sm:py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($package->customerPackages as $customerPackage)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-4 max-sm:px-2 py-3 max-sm:py-2">
                                    <a href="{{ route('customers.show', $customerPackage->customer) }}" class="font-medium text-sm max-sm:text-xs text-gray-900 dark:text-gray-100 hover:text-rose-600 dark:hover:text-rose-400">
                                        {{ $customerPackage->customer->name }}
                                    </a>
                                </td>
                                <td class="px-4 max-sm:px-2 py-3 max-sm:py-2 text-sm max-sm:text-xs text-gray-600 dark:text-gray-400">
                                    {{ format_date($customerPackage->purchased_at) }}
                                </td>
                                <td class="px-4 max-sm:px-2 py-3 max-sm:py-2 text-sm max-sm:text-xs text-gray-600 dark:text-gray-400">
                                    {{ $customerPackage->sessions_used }}/{{ $customerPackage->sessions_total }}
                                </td>
                                <td class="px-4 max-sm:px-2 py-3 max-sm:py-2">
                                    @php
                                        $statusColors = [
                                            'active' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400',
                                            'completed' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400',
                                            'expired' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400',
                                            'cancelled' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$customerPackage->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400' }}">
                                        {{ $customerPackage->status_label }}
                                    </span>
                                </td>
                                <td class="px-4 max-sm:px-2 py-3 max-sm:py-2 text-right">
                                    <a href="{{ route('customer-packages.show', $customerPackage) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                        <svg class="w-5 h-5 max-sm:w-4 max-sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8 max-sm:py-6">
                <svg class="mx-auto h-12 w-12 max-sm:h-10 max-sm:w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <p class="mt-2 text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Belum ada customer yang membeli paket ini</p>
            </div>
        @endif
    </div>
</div>
@endsection
