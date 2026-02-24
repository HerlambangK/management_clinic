<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('portal.otp_email_subject', ['name' => brand_name()]) }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f4f0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .card {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo img {
            max-height: 60px;
        }
        .logo-text {
            font-size: 24px;
            font-weight: 600;
            color: {{ brand_color('primary') }};
        }
        h1 {
            font-size: 24px;
            color: #1f2937;
            margin: 0 0 20px;
            text-align: center;
        }
        p {
            margin: 0 0 16px;
            color: #4b5563;
        }
        .otp-box {
            background: linear-gradient(135deg, {{ brand_color('primary_light') }} 0%, #fef3f2 100%);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 36px;
            font-weight: 700;
            letter-spacing: 8px;
            color: {{ brand_color('primary') }};
            margin: 0;
        }
        .otp-label {
            font-size: 14px;
            color: #6b7280;
            margin-top: 10px;
        }
        .warning {
            background: #fef3c7;
            border-radius: 8px;
            padding: 16px;
            font-size: 14px;
            color: #92400e;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="logo">
                @if(brand_logo('logo'))
                    <img src="{{ brand_logo('logo') }}" alt="{{ brand_name() }}">
                @else
                    <span class="logo-text">{{ brand_name() }}</span>
                @endif
            </div>

            <h1>{{ __('portal.otp_email_title') }}</h1>

            <p>{{ __('portal.otp_email_greeting', ['name' => $customer->name]) }}</p>

            <p>{{ __('portal.otp_email_body') }}</p>

            <div class="otp-box">
                <p class="otp-code">{{ $otp }}</p>
                <p class="otp-label">{{ __('portal.otp_email_valid', ['minutes' => 10]) }}</p>
            </div>

            <div class="warning">
                {{ __('portal.otp_email_warning') }}
            </div>

            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ brand_name() }}. {{ __('portal.all_rights_reserved') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
