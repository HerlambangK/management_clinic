<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('treatment.pdf_title') }} - {{ $record->customer->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        .container {
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e91e63;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            color: #e91e63;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 11px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #e91e63;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .info-grid {
            display: table;
            width: 100%;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            width: 35%;
            padding: 5px 0;
            font-weight: bold;
            color: #555;
        }
        .info-value {
            display: table-cell;
            padding: 5px 0;
        }
        .photos {
            margin-top: 20px;
        }
        .photos-container {
            display: table;
            width: 100%;
        }
        .photo-box {
            display: table-cell;
            width: 48%;
            text-align: center;
            vertical-align: top;
        }
        .photo-box img {
            max-width: 200px;
            max-height: 200px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .photo-label {
            font-weight: bold;
            margin-bottom: 10px;
            color: #666;
        }
        .notes-box {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            border-left: 3px solid #e91e63;
        }
        .products-list {
            margin: 0;
            padding-left: 20px;
        }
        .products-list li {
            margin-bottom: 5px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-confirmed { background: #dbeafe; color: #1e40af; }
        .badge-completed { background: #d1fae5; color: #065f46; }
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
        <div class="section">
            <h2 style="text-align: center; margin-bottom: 20px;">{{ __('treatment.pdf_title') }}</h2>
        </div>

        <!-- Customer Info -->
        <div class="section">
            <div class="section-title">{{ __('treatment.customer_info') }}</div>
            <div class="info-grid">
                <div class="info-row">
                    <span class="info-label">{{ __('common.name') }}:</span>
                    <span class="info-value">{{ $record->customer->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('common.phone') }}:</span>
                    <span class="info-value">{{ $record->customer->phone }}</span>
                </div>
                @if($record->customer->email)
                <div class="info-row">
                    <span class="info-label">{{ __('common.email') }}:</span>
                    <span class="info-value">{{ $record->customer->email }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Treatment Info -->
        <div class="section">
            <div class="section-title">{{ __('treatment.treatment_info') }}</div>
            <div class="info-grid">
                <div class="info-row">
                    <span class="info-label">{{ __('treatment.date') }}:</span>
                    <span class="info-value">{{ $record->created_at->locale(app()->getLocale())->isoFormat('dddd, D MMMM YYYY') }}</span>
                </div>
                @if($record->appointment && $record->appointment->service)
                <div class="info-row">
                    <span class="info-label">{{ __('common.service') }}:</span>
                    <span class="info-value">{{ $record->appointment->service->name }}</span>
                </div>
                @endif
                @if($record->staff)
                <div class="info-row">
                    <span class="info-label">{{ business_staff_label() }}:</span>
                    <span class="info-value">{{ $record->staff->name }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Treatment Notes -->
        @if($record->notes)
        <div class="section">
            <div class="section-title">{{ __('treatment.notes') }}</div>
            <div class="notes-box">
                {!! nl2br(e($record->notes)) !!}
            </div>
        </div>
        @endif

        <!-- Products Used -->
        @if($record->products_used && count($record->products_used) > 0)
        <div class="section">
            <div class="section-title">{{ __('treatment.products_used') }}</div>
            <ul class="products-list">
                @foreach($record->products_used as $product)
                    <li>{{ $product }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Recommendations -->
        @if($record->recommendations)
        <div class="section">
            <div class="section-title">{{ __('treatment.recommendations') }}</div>
            <div class="notes-box">
                {!! nl2br(e($record->recommendations)) !!}
            </div>
        </div>
        @endif

        <!-- Follow Up -->
        @if($record->follow_up_date)
        <div class="section">
            <div class="section-title">{{ __('treatment.follow_up') }}</div>
            <p>{{ $record->follow_up_date->locale(app()->getLocale())->isoFormat('dddd, D MMMM YYYY') }}</p>
        </div>
        @endif

        <!-- Photos -->
        @if($record->before_photo || $record->after_photo)
        <div class="section photos">
            <div class="section-title">{{ __('treatment.photos') }}</div>
            <div class="photos-container">
                @if($record->before_photo)
                <div class="photo-box">
                    <div class="photo-label">{{ __('treatment.before') }}</div>
                    <img src="{{ public_path('storage/' . $record->before_photo) }}" alt="Before">
                </div>
                @endif
                @if($record->after_photo)
                <div class="photo-box">
                    <div class="photo-label">{{ __('treatment.after') }}</div>
                    <img src="{{ public_path('storage/' . $record->after_photo) }}" alt="After">
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>{{ __('treatment.pdf_generated') }}: {{ now()->locale(app()->getLocale())->isoFormat('D MMMM YYYY, HH:mm') }}</p>
            <p>{{ brand_name() }} - {{ __('treatment.pdf_confidential') }}</p>
        </div>
    </div>
</body>
</html>
