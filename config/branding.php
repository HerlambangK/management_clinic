<?php

return [
    /*
    |--------------------------------------------------------------------------
    | White-Label Branding Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains all branding-related settings that can be customized
    | for white-labeling the application. These values can be overridden by
    | settings stored in the database.
    |
    */

    // Application Identity
    'app' => [
        'name' => env('APP_BRAND_NAME', 'GlowUp'),
        'tagline' => env('APP_BRAND_TAGLINE', 'Beauty & Wellness Management'),
        'tagline_id' => env('APP_BRAND_TAGLINE_ID', 'Manajemen Kecantikan & Wellness'),
        'description' => env('APP_BRAND_DESCRIPTION', 'Complete management system for beauty clinics, salons, and barbershops'),
        'description_id' => env('APP_BRAND_DESCRIPTION_ID', 'Sistem manajemen lengkap untuk klinik kecantikan, salon, dan barbershop'),
        'version' => env('APP_VERSION', '1.0.0'),
    ],

    // Logo Configuration
    'logo' => [
        'path' => env('APP_LOGO_PATH', null), // Path to custom logo file
        'favicon' => env('APP_FAVICON_PATH', null), // Path to favicon
        'width' => env('APP_LOGO_WIDTH', 120), // Logo width in pixels
        'height' => env('APP_LOGO_HEIGHT', 40), // Logo height in pixels
        'show_text' => env('APP_LOGO_SHOW_TEXT', true), // Show app name next to logo
    ],

    // Default Theme Colors (can be overridden per business type)
    'colors' => [
        'palette' => env('APP_COLOR_PALETTE', null),
        'primary' => env('APP_COLOR_PRIMARY', '#f43f5e'), // Rose-500
        'primary_hover' => env('APP_COLOR_PRIMARY_HOVER', '#e11d48'), // Rose-600
        'primary_light' => env('APP_COLOR_PRIMARY_LIGHT', '#fff1f2'), // Rose-50
        'secondary' => env('APP_COLOR_SECONDARY', '#6b7280'), // Gray-500
        'accent' => env('APP_COLOR_ACCENT', '#8b5cf6'), // Violet-500
        'success' => env('APP_COLOR_SUCCESS', '#22c55e'), // Green-500
        'warning' => env('APP_COLOR_WARNING', '#f59e0b'), // Amber-500
        'danger' => env('APP_COLOR_DANGER', '#ef4444'), // Red-500
        'info' => env('APP_COLOR_INFO', '#3b82f6'), // Blue-500
    ],

    // Tailwind CSS Classes (for easy theming)
    'tailwind' => [
        'primary' => env('APP_TW_PRIMARY', 'rose'),
        'gradient_from' => env('APP_TW_GRADIENT_FROM', 'from-rose-400'),
        'gradient_to' => env('APP_TW_GRADIENT_TO', 'to-rose-500'),
    ],

    // Contact Information
    'contact' => [
        'email' => env('APP_CONTACT_EMAIL', 'support@glowup.app'),
        'phone' => env('APP_CONTACT_PHONE', null),
        'whatsapp' => env('APP_CONTACT_WHATSAPP', null),
        'address' => env('APP_CONTACT_ADDRESS', null),
    ],

    // Social Media Links
    'social' => [
        'facebook' => env('APP_SOCIAL_FACEBOOK', null),
        'instagram' => env('APP_SOCIAL_INSTAGRAM', null),
        'twitter' => env('APP_SOCIAL_TWITTER', null),
        'youtube' => env('APP_SOCIAL_YOUTUBE', null),
        'tiktok' => env('APP_SOCIAL_TIKTOK', null),
        'linkedin' => env('APP_SOCIAL_LINKEDIN', null),
    ],

    // Footer Configuration
    'footer' => [
        'copyright' => env('APP_FOOTER_COPYRIGHT', '© :year :app_name. All rights reserved.'),
        'copyright_id' => env('APP_FOOTER_COPYRIGHT_ID', '© :year :app_name. Hak cipta dilindungi.'),
        'show_powered_by' => env('APP_FOOTER_SHOW_POWERED_BY', true),
        'powered_by_text' => env('APP_FOOTER_POWERED_BY', 'Powered by GlowUp'),
        'powered_by_url' => env('APP_FOOTER_POWERED_BY_URL', 'https://glowup.app'),
    ],

    // Landing Page
    'landing' => [
        'show_hero' => env('APP_LANDING_SHOW_HERO', true),
        'hero_title' => env('APP_LANDING_HERO_TITLE', null), // null = use default
        'hero_subtitle' => env('APP_LANDING_HERO_SUBTITLE', null),
        'show_features' => env('APP_LANDING_SHOW_FEATURES', true),
        'show_pricing' => env('APP_LANDING_SHOW_PRICING', false),
        'show_testimonials' => env('APP_LANDING_SHOW_TESTIMONIALS', false),
        'custom_css' => env('APP_LANDING_CUSTOM_CSS', null),
    ],

    // Email Configuration
    'email' => [
        'from_name' => env('APP_EMAIL_FROM_NAME', null), // null = use app name
        'logo_url' => env('APP_EMAIL_LOGO_URL', null),
        'footer_text' => env('APP_EMAIL_FOOTER_TEXT', null),
    ],

    // Invoice/Receipt Branding
    'invoice' => [
        'logo_path' => env('APP_INVOICE_LOGO_PATH', null),
        'header_text' => env('APP_INVOICE_HEADER_TEXT', null),
        'footer_text' => env('APP_INVOICE_FOOTER_TEXT', 'Thank you for your business!'),
        'footer_text_id' => env('APP_INVOICE_FOOTER_TEXT_ID', 'Terima kasih atas kunjungan Anda!'),
        'show_business_info' => env('APP_INVOICE_SHOW_BUSINESS_INFO', true),
    ],

    // Feature Flags
    'features' => [
        'allow_registration' => env('APP_FEATURE_REGISTRATION', false),
        'show_language_switcher' => env('APP_FEATURE_LANGUAGE_SWITCHER', true),
        'show_dark_mode' => env('APP_FEATURE_DARK_MODE', false),
        'show_notifications' => env('APP_FEATURE_NOTIFICATIONS', true),
    ],

    // Custom Scripts/Styles
    'custom' => [
        'head_scripts' => env('APP_CUSTOM_HEAD_SCRIPTS', null), // Google Analytics, etc.
        'body_scripts' => env('APP_CUSTOM_BODY_SCRIPTS', null), // Chat widgets, etc.
        'custom_css' => env('APP_CUSTOM_CSS', null),
    ],
];
