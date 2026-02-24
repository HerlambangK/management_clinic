<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (! function_exists('brand')) {
    /**
     * Get a branding configuration value.
     * First checks database settings, then falls back to config file.
     *
     * @param  string  $key  Dot notation key (e.g., 'app.name', 'colors.primary')
     * @param  mixed  $default  Default value if not found
     */
    function brand(string $key, mixed $default = null): mixed
    {
        return Cache::remember("brand.{$key}", 300, function () use ($key, $default) {
            // First try to get from database settings
            $settingKey = 'brand_'.str_replace('.', '_', $key);

            try {
                $dbValue = Setting::get($settingKey);
                if ($dbValue !== null && $dbValue !== '') {
                    return $dbValue;
                }
            } catch (\Exception $e) {
                // Database might not be ready
            }

            // Fall back to config file
            return config("branding.{$key}", $default);
        });
    }
}

if (! function_exists('brand_name')) {
    /**
     * Get the application brand name.
     */
    function brand_name(): string
    {
        return brand('app.name', 'GlowUp');
    }
}

if (! function_exists('brand_tagline')) {
    /**
     * Get the application tagline based on locale.
     */
    function brand_tagline(): string
    {
        $locale = app()->getLocale();
        $key = $locale === 'id' ? 'app.tagline_id' : 'app.tagline';

        return brand($key, 'Beauty & Wellness Management');
    }
}

if (! function_exists('brand_description')) {
    /**
     * Get the application description based on locale.
     */
    function brand_description(): string
    {
        $locale = app()->getLocale();
        $key = $locale === 'id' ? 'app.description_id' : 'app.description';

        return brand($key, '');
    }
}

if (! function_exists('brand_logo')) {
    /**
     * Get the logo URL or path.
     *
     * @param  string  $type  'main', 'favicon', 'email', 'invoice'
     */
    function brand_logo(string $type = 'main'): ?string
    {
        $key = match ($type) {
            'favicon' => 'logo.favicon',
            'email' => 'email.logo_url',
            'invoice' => 'invoice.logo_path',
            default => 'logo.path',
        };

        $path = brand($key);

        if (! $path) {
            return null;
        }

        // If it's a full URL, return as-is
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // If it's a storage path, generate URL
        if (str_starts_with($path, 'branding/')) {
            return asset('storage/'.$path);
        }

        return asset($path);
    }
}

if (! function_exists('brand_color')) {
    /**
     * Get a brand color value.
     *
     * @param  string  $name  Color name (primary, secondary, accent, etc.)
     */
    function brand_color(string $name = 'primary'): string
    {
        $store = current_store();

        if ($store && is_array($store->theme_custom) && isset($store->theme_custom[$name])) {
            return $store->theme_custom[$name];
        }

        try {
            $explicit = Setting::get('brand_colors_'.$name);
        } catch (\Exception $e) {
            $explicit = null;
        }

        if ($explicit !== null && $explicit !== '') {
            return $explicit;
        }

        $palette = brand_palette();

        if ($palette) {
            return match ($name) {
                'primary' => $palette['colors']['500'] ?? '#f43f5e',
                'primary_hover' => $palette['colors']['600'] ?? '#e11d48',
                'primary_light' => $palette['colors']['50'] ?? '#fff1f2',
                default => config("branding.colors.{$name}", '#f43f5e'),
            };
        }

        return config("branding.colors.{$name}", '#f43f5e');
    }
}

if (! function_exists('brand_palettes')) {
    /**
     * Get available color palettes.
     *
     * @return array<string, array{label: string, colors: array<string, string>}>
     */
    function brand_palettes(): array
    {
        return [
            'rose' => [
                'label' => 'Rose',
                'colors' => [
                    '50' => '#fff1f2',
                    '100' => '#ffe4e6',
                    '200' => '#fecdd3',
                    '300' => '#fda4af',
                    '400' => '#fb7185',
                    '500' => '#f43f5e',
                    '600' => '#e11d48',
                    '700' => '#be123c',
                    '800' => '#9f1239',
                    '900' => '#881337',
                ],
            ],
            'purple' => [
                'label' => 'Purple',
                'colors' => [
                    '50' => '#faf5ff',
                    '100' => '#f3e8ff',
                    '200' => '#e9d5ff',
                    '300' => '#d8b4fe',
                    '400' => '#c084fc',
                    '500' => '#a855f7',
                    '600' => '#9333ea',
                    '700' => '#7e22ce',
                    '800' => '#6b21a8',
                    '900' => '#581c87',
                ],
            ],
            'blue' => [
                'label' => 'Blue',
                'colors' => [
                    '50' => '#eff6ff',
                    '100' => '#dbeafe',
                    '200' => '#bfdbfe',
                    '300' => '#93c5fd',
                    '400' => '#60a5fa',
                    '500' => '#3b82f6',
                    '600' => '#2563eb',
                    '700' => '#1d4ed8',
                    '800' => '#1e40af',
                    '900' => '#1e3a8a',
                ],
            ],
            'emerald' => [
                'label' => 'Emerald',
                'colors' => [
                    '50' => '#ecfdf5',
                    '100' => '#d1fae5',
                    '200' => '#a7f3d0',
                    '300' => '#6ee7b7',
                    '400' => '#34d399',
                    '500' => '#10b981',
                    '600' => '#059669',
                    '700' => '#047857',
                    '800' => '#065f46',
                    '900' => '#064e3b',
                ],
            ],
            'amber' => [
                'label' => 'Amber',
                'colors' => [
                    '50' => '#fffbeb',
                    '100' => '#fef3c7',
                    '200' => '#fde68a',
                    '300' => '#fcd34d',
                    '400' => '#fbbf24',
                    '500' => '#f59e0b',
                    '600' => '#d97706',
                    '700' => '#b45309',
                    '800' => '#92400e',
                    '900' => '#78350f',
                ],
            ],
        ];
    }
}

if (! function_exists('brand_palette')) {
    /**
     * Get the active brand palette.
     *
     * @return array{key: string, label: string, colors: array<string, string>}|null
     */
    function brand_palette(): ?array
    {
        $palettes = brand_palettes();
        $store = current_store();
        $key = $store?->theme_palette;

        if (! $key) {
            $key = brand('colors.palette');
        }

        if (! $key) {
            $key = match (business_type() ?? 'clinic') {
                'salon' => 'purple',
                'barbershop' => 'blue',
                'gym' => 'emerald',
                default => 'rose',
            };
        }

        if (! isset($palettes[$key])) {
            return null;
        }

        return [
            'key' => $key,
            'label' => $palettes[$key]['label'],
            'colors' => $palettes[$key]['colors'],
        ];
    }
}

if (! function_exists('brand_tailwind')) {
    /**
     * Get Tailwind CSS class for theming.
     *
     * @param  string  $name  Class type (primary, gradient_from, gradient_to)
     */
    function brand_tailwind(string $name = 'primary'): string
    {
        return brand("tailwind.{$name}", 'rose');
    }
}

if (! function_exists('brand_contact')) {
    /**
     * Get contact information.
     *
     * @param  string  $type  Contact type (email, phone, whatsapp, address)
     */
    function brand_contact(string $type): ?string
    {
        return brand("contact.{$type}");
    }
}

if (! function_exists('brand_social')) {
    /**
     * Get social media link.
     *
     * @param  string  $platform  Platform name (facebook, instagram, twitter, etc.)
     */
    function brand_social(string $platform): ?string
    {
        return brand("social.{$platform}");
    }
}

if (! function_exists('brand_socials')) {
    /**
     * Get all configured social media links.
     */
    function brand_socials(): array
    {
        $platforms = ['facebook', 'instagram', 'twitter', 'youtube', 'tiktok', 'linkedin'];
        $socials = [];

        foreach ($platforms as $platform) {
            $url = brand_social($platform);
            if ($url) {
                $socials[$platform] = $url;
            }
        }

        return $socials;
    }
}

if (! function_exists('brand_copyright')) {
    /**
     * Get the copyright text with replacements.
     */
    function brand_copyright(): string
    {
        $locale = app()->getLocale();
        $key = $locale === 'id' ? 'footer.copyright_id' : 'footer.copyright';

        $text = brand($key, '© :year :app_name. All rights reserved.');

        return str_replace(
            [':year', ':app_name'],
            [date('Y'), brand_name()],
            $text
        );
    }
}

if (! function_exists('brand_feature')) {
    /**
     * Check if a feature is enabled.
     *
     * @param  string  $feature  Feature name
     */
    function brand_feature(string $feature): bool
    {
        return (bool) brand("features.{$feature}", false);
    }
}

if (! function_exists('brand_custom_script')) {
    /**
     * Get custom script for injection.
     *
     * @param  string  $location  Script location (head, body)
     */
    function brand_custom_script(string $location = 'head'): ?string
    {
        $key = $location === 'body' ? 'custom.body_scripts' : 'custom.head_scripts';

        return brand($key);
    }
}

if (! function_exists('brand_custom_css')) {
    /**
     * Get custom CSS.
     */
    function brand_custom_css(): ?string
    {
        return brand('custom.custom_css');
    }
}

if (! function_exists('clear_brand_cache')) {
    /**
     * Clear all branding cache.
     */
    function clear_brand_cache(): void
    {
        // Get all potential cache keys
        $keys = [
            'app.name', 'app.tagline', 'app.tagline_id', 'app.description', 'app.description_id',
            'logo.path', 'logo.favicon', 'logo.width', 'logo.height', 'logo.show_text',
            'colors.primary', 'colors.primary_hover', 'colors.primary_light',
            'colors.secondary', 'colors.accent', 'colors.success', 'colors.warning',
            'colors.danger', 'colors.info', 'colors.palette',
            'tailwind.primary', 'tailwind.gradient_from', 'tailwind.gradient_to',
            'contact.email', 'contact.phone', 'contact.whatsapp', 'contact.address',
            'social.facebook', 'social.instagram', 'social.twitter', 'social.youtube',
            'social.tiktok', 'social.linkedin',
            'footer.copyright', 'footer.copyright_id', 'footer.show_powered_by',
            'footer.powered_by_text', 'footer.powered_by_url',
            'features.allow_registration', 'features.show_language_switcher',
            'features.show_dark_mode', 'features.show_notifications',
            'custom.head_scripts', 'custom.body_scripts', 'custom.custom_css',
        ];

        foreach ($keys as $key) {
            Cache::forget("brand.{$key}");
        }
    }
}
