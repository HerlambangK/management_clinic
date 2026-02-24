<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('treatment.pdf_history_title') }} - {{ $customer->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        .container {
            padding: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #e91e63;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 20px;
            color: #e91e63;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 10px;
        }
        .customer-info {
            background: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .customer-info h2 {
            font-size: 14px;
            color: #333;
            margin-bottom: 10px;
        }
        .customer-info p {
            margin: 3px 0;
        }
        .record-card {
            border: 1px solid #ddd;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .record-header {
            background: #f5f5f5;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .record-header h3 {
            font-size: 12px;
            color: #e91e63;
        }
        .record-header .date {
            font-size: 10px;
            color: #666;
        }
        .record-body {
            padding: 10px;
        }
        .record-section {
            margin-bottom: 10px;
        }
        .record-section-title {
            font-weight: bold;
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .products-list {
            margin: 0;
            padding-left: 15px;
        }
        .products-list li {
            margin-bottom: 2px;
        }
        .photos-row {
            display: table;
            width: 100%;
            margin-top: 10px;
        }
        .photo-cell {
            display: table-cell;
            width: 48%;
            text-align: center;
            vertical-align: top;
        }
        .photo-cell img {
            max-width: 150px;
            max-height: 150px;
            border: 1px solid #ddd;
        }
        .photo-cell .label {
            font-size: 9px;
            color: #666;
            margin-bottom: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        .summary-box {
            background: #fff3f8;
            border: 1px solid #e91e63;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .summary-box h3 {
            font-size: 12px;
            color: #e91e63;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ brand_name() }}</h1>
            <p>{{ brand_contact('address') }}</p>
            @if(brand_contact('phone'))
                <p>{{ __('common.phone') }}: {{ brand_contact('phone') }}</p>
            @endif
        </div>

        <!-- Title -->
        <h2 style="text-align: center; margin-bottom: 20px; font-size: 16px;">{{ __('treatment.pdf_history_title') }}</h2>

        <!-- Customer Info -->
        <div class="customer-info">
            <h2>{{ __('treatment.customer_info') }}</h2>
            <p><strong>{{ __('common.name') }}:</strong> {{ $customer->name }}</p>
            <p><strong>{{ __('common.phone') }}:</strong> {{ $customer->phone }}</p>
            @if($customer->email)
                <p><strong>{{ __('common.email') }}:</strong> {{ $customer->email }}</p>
            @endif
        </div>

        <!-- Summary -->
        <div class="summary-box">
            <h3>{{ __('treatment.summary') }}</h3>
            <p>{{ __('treatment.total_records') }}: {{ $records->count() }}</p>
            <p>{{ __('treatment.first_visit') }}: {{ $records->last()->created_at->locale(app()->getLocale())->isoFormat('D MMMM YYYY') }}</p>
            <p>{{ __('treatment.last_visit') }}: {{ $records->first()->created_at->locale(app()->getLocale())->isoFormat('D MMMM YYYY') }}</p>
        </div>

        <!-- Treatment Records -->
        @foreach($records as $record)
        <div class="record-card">
            <div class="record-header">
                <h3>
                    @if($record->appointment && $record->appointment->service)
                        {{ $record->appointment->service->name }}
                    @else
                        {{ __('treatment.treatment') }}
                    @endif
                </h3>
                <div class="date">{{ $record->created_at->locale(app()->getLocale())->isoFormat('dddd, D MMMM YYYY') }}</div>
            </div>
            <div class="record-body">
                @if($record->staff)
                <div class="record-section">
                    <div class="record-section-title">{{ business_staff_label() }}</div>
                    <p>{{ $record->staff->name }}</p>
                </div>
                @endif

                @if($record->notes)
                <div class="record-section">
                    <div class="record-section-title">{{ __('treatment.notes') }}</div>
                    <p>{!! nl2br(e($record->notes)) !!}</p>
                </div>
                @endif

                @if($record->products_used && count($record->products_used) > 0)
                <div class="record-section">
                    <div class="record-section-title">{{ __('treatment.products_used') }}</div>
                    <ul class="products-list">
                        @foreach($record->products_used as $product)
                            <li>{{ $product }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if($record->recommendations)
                <div class="record-section">
                    <div class="record-section-title">{{ __('treatment.recommendations') }}</div>
                    <p>{!! nl2br(e($record->recommendations)) !!}</p>
                </div>
                @endif

                @if($record->follow_up_date)
                <div class="record-section">
                    <div class="record-section-title">{{ __('treatment.follow_up') }}</div>
                    <p>{{ $record->follow_up_date->locale(app()->getLocale())->isoFormat('D MMMM YYYY') }}</p>
                </div>
                @endif

                @if($record->before_photo || $record->after_photo)
                <div class="photos-row">
                    @if($record->before_photo)
                    <div class="photo-cell">
                        <div class="label">{{ __('treatment.before') }}</div>
                        <img src="{{ public_path('storage/' . $record->before_photo) }}" alt="Before">
                    </div>
                    @endif
                    @if($record->after_photo)
                    <div class="photo-cell">
                        <div class="label">{{ __('treatment.after') }}</div>
                        <img src="{{ public_path('storage/' . $record->after_photo) }}" alt="After">
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endforeach

        <!-- Footer -->
        <div class="footer">
            <p>{{ __('treatment.pdf_generated') }}: {{ now()->locale(app()->getLocale())->isoFormat('D MMMM YYYY, HH:mm') }}</p>
            <p>{{ brand_name() }} - {{ __('treatment.pdf_confidential') }}</p>
        </div>
    </div>
</body>
</html>
