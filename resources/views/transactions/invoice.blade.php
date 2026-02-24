<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $transaction->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            background: #fff;
        }

        .invoice {
            width: 302px; /* 80mm at 96dpi */
            max-width: 302px;
            margin: 0 auto;
            padding: 15px;
            background: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #ccc;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 11px;
            color: #666;
        }

        .info {
            margin-bottom: 15px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .info-label {
            color: #666;
        }

        .info-value {
            font-weight: 500;
        }

        .divider {
            border-top: 1px dashed #ccc;
            margin: 10px 0;
        }

        .items {
            margin-bottom: 15px;
        }

        .item {
            margin-bottom: 8px;
        }

        .item-name {
            font-weight: 500;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #666;
            margin-top: 2px;
        }

        .item-discount {
            color: #dc2626;
            font-size: 10px;
        }

        .totals {
            margin-bottom: 15px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .total-row.grand {
            font-size: 14px;
            font-weight: bold;
            padding-top: 8px;
            border-top: 1px solid #333;
        }

        .discount-text {
            color: #dc2626;
        }

        .payments {
            margin-bottom: 15px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
        }

        .payments h3 {
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
            color: #666;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 11px;
        }

        .change {
            color: #2563eb;
            font-weight: 600;
        }

        .footer {
            text-align: center;
            padding-top: 15px;
            border-top: 1px dashed #ccc;
        }

        .footer p {
            font-size: 11px;
            color: #666;
            margin-bottom: 3px;
        }

        .footer .thank-you {
            font-size: 12px;
            font-weight: 600;
            color: #333;
            margin-top: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-paid {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background: #fef9c3;
            color: #854d0e;
        }

        .status-partial {
            background: #ffedd5;
            color: #9a3412;
        }

        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }

            html, body {
                width: 80mm;
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .invoice {
                width: 80mm;
                max-width: 80mm;
                padding: 2mm 3mm;
                margin: 0;
            }

            .no-print {
                display: none !important;
            }
        }

        @media screen {
            body {
                background: #e5e5e5;
                padding: 20px;
            }

            .invoice {
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
        }

        .print-button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            background: #f43f5e;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .print-button:hover {
            background: #e11d48;
        }
    </style>
</head>
<body>
    <div class="invoice">
        <!-- Print Button -->
        <button class="print-button no-print" onclick="window.print()">
            Cetak Invoice
        </button>

        <!-- Header -->
        <div class="header">
            @if(brand_logo('invoice') ?? brand_logo())
                <img src="{{ brand_logo('invoice') ?? brand_logo() }}" alt="{{ brand_name() }}" style="max-height: 50px; margin-bottom: 8px;">
            @endif
            <h1>{{ \App\Models\Setting::get('business_name', brand_name()) }}</h1>
            <p>{{ brand_tagline() }}</p>
            @if(\App\Models\Setting::get('business_address') || brand_contact('address'))
                <p>{{ \App\Models\Setting::get('business_address') ?? brand_contact('address') }}</p>
            @endif
            @if(\App\Models\Setting::get('business_phone') || brand_contact('phone'))
                <p>Telp: {{ \App\Models\Setting::get('business_phone') ?? brand_contact('phone') }}</p>
            @endif
        </div>

        <!-- Invoice Info -->
        <div class="info">
            <div class="info-row">
                <span class="info-label">No. Invoice</span>
                <span class="info-value">{{ $transaction->invoice_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('transaction.date') }}</span>
                <span class="info-value">{{ format_datetime($transaction->created_at) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('transaction.customer') }}</span>
                <span class="info-value">{{ $transaction->customer?->name ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('transaction.cashier') }}</span>
                <span class="info-value">{{ $transaction->cashier->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('common.status') }}</span>
                <span class="status-badge status-{{ $transaction->status }}">{{ $transaction->status_label }}</span>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Items -->
        <div class="items">
            @foreach($transaction->items as $item)
                <div class="item">
                    <div class="item-name">{{ $item->item_name }}</div>
                    <div class="item-details">
                        <span>{{ format_number($item->quantity) }} x {{ format_currency($item->unit_price) }}</span>
                        <span>{{ format_currency($item->total_price + $item->discount) }}</span>
                    </div>
                    @if($item->discount > 0)
                        <div class="item-discount">
                            Diskon: -{{ format_currency($item->discount) }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="divider"></div>

        <!-- Totals -->
        <div class="totals">
            <div class="total-row">
                <span>{{ __('transaction.subtotal') }}</span>
                <span>{{ format_currency($transaction->subtotal) }}</span>
            </div>
            @if($transaction->discount_amount > 0)
                <div class="total-row">
                    <span class="discount-text">
                        Diskon
                        @if($transaction->discount_type)
                            ({{ $transaction->discount_type }})
                        @endif
                    </span>
                    <span class="discount-text">-{{ format_currency($transaction->discount_amount) }}</span>
                </div>
            @endif
            @if($transaction->points_used > 0)
                <div class="total-row">
                    <span class="discount-text">
                        {{ __('loyalty.points_discount') }} ({{ format_number($transaction->points_used) }} poin)
                    </span>
                    <span class="discount-text">-{{ format_currency($transaction->points_discount) }}</span>
                </div>
            @endif
            @if($transaction->tax_amount > 0)
                <div class="total-row">
                    <span>{{ __('transaction.tax') }}</span>
                    <span>{{ format_currency($transaction->tax_amount) }}</span>
                </div>
            @endif
            <div class="total-row grand">
                <span>{{ __('common.total') }}</span>
                <span>{{ format_currency($transaction->total_amount) }}</span>
            </div>
        </div>

        <!-- Payments -->
        @if($transaction->payments->count() > 0)
            <div class="payments">
                <h3>Pembayaran</h3>
                @foreach($transaction->payments as $payment)
                    <div class="payment-row">
                        <span>{{ $payment->payment_method_label }}</span>
                        <span>{{ format_currency($payment->amount) }}</span>
                    </div>
                @endforeach
                @if($transaction->change_amount > 0)
                    <div class="divider"></div>
                    <div class="payment-row change">
                        <span>Kembalian</span>
                        <span>{{ format_currency($transaction->change_amount) }}</span>
                    </div>
                @endif
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
            <p>Simpan struk ini sebagai bukti pembayaran</p>
            <p class="thank-you">{{ app()->getLocale() === 'id' ? brand('invoice.footer_text_id', 'Terima kasih atas kunjungan Anda!') : brand('invoice.footer_text', 'Thank you for your business!') }}</p>
            <p style="margin-top: 10px; font-size: 10px;">{{ format_datetime(now(), 'd/m/Y H:i:s') }}</p>
        </div>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
