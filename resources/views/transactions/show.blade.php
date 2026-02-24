@extends('layouts.dashboard')

@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')

@section('content')
<div class="space-y-6 max-sm:space-y-4">
    <!-- Header -->
    <div class="flex flex-row max-sm:flex-col items-center max-sm:items-start justify-between gap-4 max-sm:gap-3">
        <div>
            <a href="{{ route('transactions.index') }}" class="inline-flex items-center gap-2 text-sm max-sm:text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition mb-2 max-sm:mb-1.5">
                <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar Transaksi
            </a>
            <h2 class="text-2xl max-sm:text-lg font-bold text-gray-900 dark:text-gray-100">{{ $transaction->invoice_number }}</h2>
        </div>
        <div class="flex flex-row max-sm:flex-col items-center gap-2 max-sm:w-full">
            @if($transaction->status === 'paid')
                <a href="{{ route('transactions.invoice', $transaction) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition max-sm:w-full max-sm:justify-center">
                    <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Invoice
                </a>
            @endif
            @if(in_array($transaction->status, ['pending', 'partial']))
                <form action="{{ route('transactions.cancel', $transaction) }}" method="POST" class="inline max-sm:w-full" onsubmit="return confirm('Yakin ingin membatalkan transaksi ini?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 max-sm:px-3 max-sm:py-1.5 bg-white dark:bg-gray-700 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 text-sm max-sm:text-xs font-medium rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30 transition max-sm:w-full max-sm:justify-center">
                        <svg class="w-4 h-4 max-sm:w-3.5 max-sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batalkan
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm max-sm:text-xs">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg text-sm max-sm:text-xs">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-3 max-lg:grid-cols-1 gap-6 max-sm:gap-4">
        <!-- Left Column: Transaction Details -->
        <div class="col-span-2 max-lg:col-span-1 space-y-6 max-sm:space-y-4">
            <!-- Transaction Info -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
                <div class="flex items-center justify-between mb-4 max-sm:mb-3">
                    <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100">Informasi Transaksi</h3>
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400',
                            'partial' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-400',
                            'paid' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400',
                            'cancelled' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                            'refunded' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400',
                        ];
                    @endphp
                    <span class="inline-flex items-center px-3 max-sm:px-2 py-1 max-sm:py-0.5 rounded-full text-sm max-sm:text-xs font-medium {{ $statusColors[$transaction->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $transaction->status_label }}
                    </span>
                </div>

                <div class="grid grid-cols-2 max-sm:grid-cols-1 gap-4 max-sm:gap-3">
                    <div>
                        <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('transaction.customer') }}</p>
                        @if($transaction->customer)
                            <a href="{{ route('customers.show', $transaction->customer) }}" class="font-medium text-sm text-gray-900 dark:text-gray-100 hover:text-rose-600 dark:hover:text-rose-400">
                                {{ $transaction->customer->name }}
                            </a>
                            <p class="text-xs max-sm:text-[10px] text-gray-500 dark:text-gray-400">{{ $transaction->customer->phone }}</p>
                        @else
                            <p class="font-medium text-sm text-gray-400 dark:text-gray-500">-</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('transaction.date') }}</p>
                        <p class="font-medium text-sm text-gray-900 dark:text-gray-100">{{ format_datetime($transaction->created_at) }}</p>
                    </div>
                    <div>
                        <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('transaction.cashier') }}</p>
                        <p class="font-medium text-sm text-gray-900 dark:text-gray-100">{{ $transaction->cashier?->name ?? '-' }}</p>
                    </div>
                    @if($transaction->appointment)
                        <div>
                            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Appointment</p>
                            <a href="{{ route('appointments.show', $transaction->appointment) }}" class="font-medium text-sm text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300">
                                {{ $transaction->appointment->service?->name ?? '-' }}
                            </a>
                        </div>
                    @endif
                    @if($transaction->paid_at)
                        <div>
                            <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">Tanggal Lunas</p>
                            <p class="font-medium text-sm text-gray-900 dark:text-gray-100">{{ format_datetime($transaction->paid_at) }}</p>
                        </div>
                    @endif
                </div>

                @if($transaction->notes)
                    <div class="mt-4 max-sm:mt-3 pt-4 max-sm:pt-3 border-t border-gray-100 dark:border-gray-700">
                        <p class="text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('transaction.notes') }}</p>
                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $transaction->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Items -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 max-sm:px-4 py-4 max-sm:py-3 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100">Item Transaksi</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-left text-xs max-sm:text-[10px] font-medium text-gray-500 dark:text-gray-400 uppercase">Item</th>
                                <th class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-right text-xs max-sm:text-[10px] font-medium text-gray-500 dark:text-gray-400 uppercase">Harga</th>
                                <th class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-center text-xs max-sm:text-[10px] font-medium text-gray-500 dark:text-gray-400 uppercase">Qty</th>
                                <th class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-right text-xs max-sm:text-[10px] font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('transaction.discount') }}</th>
                                <th class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-right text-xs max-sm:text-[10px] font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('transaction.subtotal') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($transaction->items as $item)
                                <tr>
                                    <td class="px-6 max-sm:px-3 py-4 max-sm:py-3">
                                        <div class="flex items-center gap-2 max-sm:gap-1">
                                            @php
                                                $typeColors = [
                                                    'service' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400',
                                                    'package' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-400',
                                                    'product' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400',
                                                    'other' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                                ];
                                                $typeLabels = [
                                                    'service' => 'Layanan',
                                                    'package' => 'Paket',
                                                    'product' => 'Produk',
                                                    'other' => 'Lainnya',
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center px-2 max-sm:px-1.5 py-0.5 rounded text-xs max-sm:text-[10px] font-medium {{ $typeColors[$item->item_type] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                                {{ $typeLabels[$item->item_type] ?? $item->item_type }}
                                            </span>
                                            <span class="font-medium text-sm max-sm:text-xs text-gray-900 dark:text-gray-100">{{ $item->item_name }}</span>
                                        </div>
                                        @if($item->notes)
                                            <p class="text-xs max-sm:text-[10px] text-gray-500 dark:text-gray-400 mt-1">{{ $item->notes }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 max-sm:px-3 py-4 max-sm:py-3 text-right text-sm max-sm:text-xs text-gray-900 dark:text-gray-100">{{ $item->formatted_unit_price }}</td>
                                    <td class="px-6 max-sm:px-3 py-4 max-sm:py-3 text-center text-sm max-sm:text-xs text-gray-900 dark:text-gray-100">{{ $item->quantity }}</td>
                                    <td class="px-6 max-sm:px-3 py-4 max-sm:py-3 text-right text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">
                                        @if($item->discount > 0)
                                            -{{ $item->formatted_discount }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 max-sm:px-3 py-4 max-sm:py-3 text-right text-sm max-sm:text-xs font-medium text-gray-900 dark:text-gray-100">{{ $item->formatted_total_price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <td colspan="4" class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-right text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('transaction.subtotal') }}</td>
                                <td class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-right text-sm max-sm:text-xs font-medium text-gray-900 dark:text-gray-100">{{ $transaction->formatted_subtotal }}</td>
                            </tr>
                            @if($transaction->discount_amount > 0)
                                <tr>
                                    <td colspan="4" class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-right text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">
                                        Diskon
                                        @if($transaction->discount_type)
                                            <span class="text-xs max-sm:text-[10px]">({{ $transaction->discount_type }})</span>
                                        @endif
                                    </td>
                                    <td class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-right text-sm max-sm:text-xs font-medium text-red-600 dark:text-red-400">-{{ $transaction->formatted_discount_amount }}</td>
                                </tr>
                            @endif
                            @if($transaction->points_used > 0)
                                <tr>
                                    <td colspan="4" class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-right text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ __('loyalty.points_discount') }}
                                            <span class="text-xs max-sm:text-[10px]">({{ format_number($transaction->points_used) }} {{ __('loyalty.points') }})</span>
                                        </span>
                                    </td>
                                    <td class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-right text-sm max-sm:text-xs font-medium text-purple-600 dark:text-purple-400">-{{ format_currency($transaction->points_discount) }}</td>
                                </tr>
                            @endif
                            @if($transaction->tax_amount > 0)
                                <tr>
                                    <td colspan="4" class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-right text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ __('transaction.tax') }}</td>
                                    <td class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-right text-sm max-sm:text-xs font-medium text-gray-900 dark:text-gray-100">{{ format_currency($transaction->tax_amount) }}</td>
                                </tr>
                            @endif
                            <tr class="border-t border-gray-200 dark:border-gray-600">
                                <td colspan="4" class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-right text-base max-sm:text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('common.total') }}</td>
                                <td class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-right text-lg max-sm:text-base font-bold text-rose-600 dark:text-rose-400">{{ $transaction->formatted_total_amount }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Payments History -->
            @if($transaction->payments->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 max-sm:px-4 py-4 max-sm:py-3 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100">Riwayat Pembayaran</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-left text-xs max-sm:text-[10px] font-medium text-gray-500 dark:text-gray-400 uppercase">Tanggal</th>
                                    <th class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-left text-xs max-sm:text-[10px] font-medium text-gray-500 dark:text-gray-400 uppercase">Metode</th>
                                    <th class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-left text-xs max-sm:text-[10px] font-medium text-gray-500 dark:text-gray-400 uppercase">Referensi</th>
                                    <th class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-left text-xs max-sm:text-[10px] font-medium text-gray-500 dark:text-gray-400 uppercase">Diterima Oleh</th>
                                    <th class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-right text-xs max-sm:text-[10px] font-medium text-gray-500 dark:text-gray-400 uppercase">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($transaction->payments as $payment)
                                    <tr>
                                        <td class="px-6 max-sm:px-3 py-4 max-sm:py-3 text-sm max-sm:text-xs text-gray-900 dark:text-gray-100">{{ format_datetime($payment->paid_at) }}</td>
                                        <td class="px-6 max-sm:px-3 py-4 max-sm:py-3">
                                            <span class="inline-flex items-center px-2 max-sm:px-1.5 py-1 max-sm:py-0.5 rounded-full text-xs max-sm:text-[10px] font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                                {{ $payment->payment_method_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 max-sm:px-3 py-4 max-sm:py-3 text-sm max-sm:text-xs text-gray-500 dark:text-gray-400">{{ $payment->reference_number ?? '-' }}</td>
                                        <td class="px-6 max-sm:px-3 py-4 max-sm:py-3 text-sm max-sm:text-xs text-gray-900 dark:text-gray-100">{{ $payment->receiver->name }}</td>
                                        <td class="px-6 max-sm:px-3 py-4 max-sm:py-3 text-right text-sm max-sm:text-xs font-medium text-green-600 dark:text-green-400">{{ $payment->formatted_amount }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <td colspan="4" class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-right text-sm max-sm:text-xs font-medium text-gray-900 dark:text-gray-100">Total Terbayar</td>
                                    <td class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-right text-sm max-sm:text-xs font-bold text-green-600 dark:text-green-400">{{ $transaction->formatted_paid_amount }}</td>
                                </tr>
                                @if($transaction->outstanding_amount > 0)
                                    <tr>
                                        <td colspan="4" class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-right text-sm max-sm:text-xs font-medium text-gray-900 dark:text-gray-100">Sisa</td>
                                        <td class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-right text-sm max-sm:text-xs font-bold text-red-600 dark:text-red-400">{{ $transaction->formatted_outstanding_amount }}</td>
                                    </tr>
                                @endif
                                @if($transaction->change_amount > 0)
                                    <tr>
                                        <td colspan="4" class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-right text-sm max-sm:text-xs font-medium text-gray-900 dark:text-gray-100">Kembalian</td>
                                        <td class="px-6 max-sm:px-3 py-3 max-sm:py-2 text-right text-sm max-sm:text-xs font-bold text-blue-600 dark:text-blue-400">{{ format_currency($transaction->change_amount) }}</td>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Payment Form -->
        <div class="space-y-6 max-sm:space-y-4">
            <!-- Payment Summary Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4">
                <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">Ringkasan Pembayaran</h3>

                <div class="space-y-3 max-sm:space-y-2">
                    <div class="flex justify-between text-sm max-sm:text-xs">
                        <span class="text-gray-500 dark:text-gray-400">Total Tagihan</span>
                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $transaction->formatted_total_amount }}</span>
                    </div>
                    <div class="flex justify-between text-sm max-sm:text-xs">
                        <span class="text-gray-500 dark:text-gray-400">Terbayar</span>
                        <span class="font-semibold text-green-600 dark:text-green-400">{{ $transaction->formatted_paid_amount }}</span>
                    </div>
                    @if($transaction->outstanding_amount > 0)
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-3 max-sm:pt-2">
                            <div class="flex justify-between">
                                <span class="font-medium text-sm max-sm:text-xs text-gray-900 dark:text-gray-100">Sisa Tagihan</span>
                                <span class="font-bold text-lg max-sm:text-base text-red-600 dark:text-red-400">{{ $transaction->formatted_outstanding_amount }}</span>
                            </div>
                        </div>
                    @elseif($transaction->status === 'paid')
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-3 max-sm:pt-2">
                            <div class="flex items-center justify-center gap-2 text-green-600 dark:text-green-400">
                                <svg class="w-5 h-5 max-sm:w-4 max-sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-semibold text-sm max-sm:text-xs">LUNAS</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Form -->
            @if(in_array($transaction->status, ['pending', 'partial']))
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-sm:p-4" x-data="paymentForm()">
                    <h3 class="text-lg max-sm:text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 max-sm:mb-3">Terima Pembayaran</h3>

                    <form action="{{ route('transactions.pay', $transaction) }}" method="POST">
                        @csrf

                        <div class="space-y-4 max-sm:space-y-3">
                            <div>
                                <label class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">Jumlah <span class="text-red-500">*</span></label>
                                <input
                                    type="text"
                                    x-model="amountDisplay"
                                    @input="amount = parseNumber($event.target.value); amountDisplay = formatNumber(amount)"
                                    inputmode="numeric"
                                    class="w-full px-4 max-sm:px-3 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                    required
                                >
                                <input type="hidden" name="amount" :value="amount">
                                <div class="flex gap-2 mt-2">
                                    <button type="button" @click="setAmount({{ $transaction->outstanding_amount }})" class="px-2 max-sm:px-1.5 py-1 max-sm:py-0.5 text-xs max-sm:text-[10px] bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600">
                                        Pas
                                    </button>
                                    <button type="button" @click="roundUp(50000)" class="px-2 max-sm:px-1.5 py-1 max-sm:py-0.5 text-xs max-sm:text-[10px] bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600">
                                        50rb
                                    </button>
                                    <button type="button" @click="roundUp(100000)" class="px-2 max-sm:px-1.5 py-1 max-sm:py-0.5 text-xs max-sm:text-[10px] bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600">
                                        100rb
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">Metode Pembayaran <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select name="payment_method" class="w-full pl-4 max-sm:pl-3 pr-10 max-sm:pr-8 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition appearance-none bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" required>
                                        @foreach(\App\Models\Transaction::PAYMENT_METHODS as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 max-sm:pr-2">
                                        <svg class="h-4 w-4 max-sm:h-3.5 max-sm:w-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">No. Referensi</label>
                                <input
                                    type="text"
                                    name="reference_number"
                                    placeholder="No. kartu, no. transfer, dll"
                                    class="w-full px-4 max-sm:px-3 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                >
                            </div>

                            <div>
                                <label class="block text-sm max-sm:text-xs font-medium text-gray-700 dark:text-gray-300 mb-2 max-sm:mb-1.5">{{ __('transaction.payment_notes') }}</label>
                                <input
                                    type="text"
                                    name="notes"
                                    placeholder="{{ __('transaction.payment_notes') }}"
                                    class="w-full px-4 max-sm:px-3 py-2.5 max-sm:py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                >
                            </div>

                            <!-- Change Display -->
                            <div x-show="amount > {{ $transaction->outstanding_amount }}" class="p-3 max-sm:p-2 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg">
                                <div class="flex justify-between">
                                    <span class="text-sm max-sm:text-xs text-blue-700 dark:text-blue-400">Kembalian</span>
                                    <span class="font-bold text-sm max-sm:text-xs text-blue-700 dark:text-blue-400" x-text="formatRupiah(amount - {{ $transaction->outstanding_amount }})"></span>
                                </div>
                            </div>

                            <button type="submit" class="w-full px-4 py-3 max-sm:py-2 bg-green-500 text-white text-sm font-medium rounded-lg hover:bg-green-600 transition">
                                Terima Pembayaran
                            </button>
                        </div>
                    </form>
                </div>

                @push('scripts')
                <script>
                    function paymentForm() {
                        return {
                            amount: {{ $transaction->outstanding_amount }},
                            amountDisplay: '',

                            init() {
                                this.amountDisplay = this.formatNumber(this.amount);
                            },

                            setAmount(value) {
                                this.amount = value;
                                this.amountDisplay = this.formatNumber(value);
                            },

                            roundUp(denominator) {
                                const outstanding = {{ $transaction->outstanding_amount }};
                                this.amount = Math.ceil(outstanding / denominator) * denominator;
                                this.amountDisplay = this.formatNumber(this.amount);
                            },

                            formatNumber(value) {
                                if (!value || value === 0) return '';
                                return new Intl.NumberFormat('id-ID').format(value);
                            },

                            parseNumber(value) {
                                return parseInt(String(value).replace(/\D/g, '')) || 0;
                            },

                            formatRupiah(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        };
                    }
                </script>
                @endpush
            @endif
        </div>
    </div>
</div>
@endsection
