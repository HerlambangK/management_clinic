<?php

use App\Models\Setting;
use Illuminate\Support\Facades\App;

if (! function_exists('business_type')) {
    /**
     * Get the current business type
     */
    function business_type(): ?string
    {
        return Setting::get('business_type');
    }
}

if (! function_exists('business_config')) {
    /**
     * Get business configuration for current or specified type
     *
     * @param  string|null  $key  Dot notation key (e.g., 'theme.primary')
     * @param  string|null  $type  Business type (defaults to current)
     */
    function business_config(?string $key = null, ?string $type = null): mixed
    {
        $type = $type ?? business_type() ?? config('business.default');
        $config = config("business.types.{$type}");

        if ($key === null) {
            return $config;
        }

        return data_get($config, $key);
    }
}

if (! function_exists('business_label')) {
    /**
     * Get a localized label from business config
     *
     * @param  string  $key  The label key (e.g., 'staff_label', 'name')
     * @param  string|null  $type  Business type (defaults to current)
     */
    function business_label(string $key, ?string $type = null): string
    {
        $locale = App::getLocale();
        $config = business_config(null, $type);

        // Check for locale-specific key first (e.g., 'name_en')
        if ($locale === 'en' && isset($config[$key.'_en'])) {
            return $config[$key.'_en'];
        }

        return $config[$key] ?? '';
    }
}

if (! function_exists('business_theme')) {
    /**
     * Get theme configuration for current business type
     *
     * @param  string|null  $key  Theme key (e.g., 'primary', 'button')
     */
    function business_theme(?string $key = null): mixed
    {
        $theme = business_config('theme');

        if ($key === null) {
            return $theme;
        }

        return $theme[$key] ?? null;
    }
}

if (! function_exists('business_profile_fields')) {
    /**
     * Get profile fields configuration for current business type
     */
    function business_profile_fields(): array
    {
        return business_config('profile_fields') ?? [];
    }
}

if (! function_exists('business_profile_options')) {
    /**
     * Get profile field options with proper locale
     *
     * @param  string  $field  Field name (e.g., 'type', 'concerns')
     */
    function business_profile_options(string $field): array
    {
        $locale = App::getLocale();
        $fieldConfig = business_config("profile_fields.{$field}");

        if (! $fieldConfig || ! isset($fieldConfig['options'])) {
            return [];
        }

        $options = [];
        foreach ($fieldConfig['options'] as $key => $labels) {
            $options[$key] = $labels[$locale] ?? $labels['id'] ?? $key;
        }

        return $options;
    }
}

if (! function_exists('business_profile_label')) {
    /**
     * Get profile field label with proper locale
     *
     * @param  string  $field  Field name (e.g., 'type', 'concerns')
     */
    function business_profile_label(string $field): string
    {
        $locale = App::getLocale();
        $fieldConfig = business_config("profile_fields.{$field}");

        if (! $fieldConfig) {
            return '';
        }

        if ($locale === 'en' && isset($fieldConfig['label_en'])) {
            return $fieldConfig['label_en'];
        }

        return $fieldConfig['label'] ?? '';
    }
}

if (! function_exists('is_setup_completed')) {
    /**
     * Check if initial setup has been completed
     */
    function is_setup_completed(): bool
    {
        return (bool) Setting::get('setup_completed', false);
    }
}

if (! function_exists('business_types')) {
    /**
     * Get all available business types
     */
    function business_types(): array
    {
        return array_keys(config('business.types', []));
    }
}
