<?php

use App\Models\Setting;
use App\Models\Store;
use Illuminate\Support\Facades\Cache;

if (! function_exists('is_setup_completed')) {
    /**
     * Check if the initial setup has been completed.
     */
    function is_setup_completed(): bool
    {
        return Cache::remember('setup_completed', 60, function () {
            try {
                return (bool) Setting::get('setup_completed', false);
            } catch (\Exception $e) {
                // Database might not be ready yet
                return false;
            }
        });
    }
}

if (! function_exists('current_store')) {
    /**
     * Get the current store from the request context.
     */
    function current_store(): ?Store
    {
        if (app()->bound('currentStore')) {
            return app('currentStore');
        }

        if (! app()->bound('request')) {
            return null;
        }

        $request = app('request');

        if (! method_exists($request, 'hasSession') || ! $request->hasSession()) {
            return null;
        }

        $storeId = $request->session()->get('store_id');

        if (! $storeId) {
            return null;
        }

        try {
            $store = Store::query()->find((int) $storeId);
        } catch (\Exception $e) {
            return null;
        }

        app()->instance('currentStore', $store);

        return $store;
    }
}

if (! function_exists('business_type')) {
    /**
     * Get the current business type.
     */
    function business_type(): ?string
    {
        $store = current_store();

        if ($store) {
            return $store->business_type;
        }

        if (app()->bound('request')) {
            $request = app('request');

            if (method_exists($request, 'hasSession') && $request->hasSession()) {
                $filter = $request->session()->get('business_type_filter');

                if ($filter) {
                    return $filter;
                }
            }
        }

        return Cache::remember('business_type', 60, function () {
            try {
                return Setting::get('business_type');
            } catch (\Exception $e) {
                return null;
            }
        });
    }
}

if (! function_exists('business_config')) {
    /**
     * Get configuration for the current business type.
     *
     * @param  string|null  $key  Dot notation key to get specific config value
     * @param  mixed  $default  Default value if key not found
     */
    function business_config(?string $key = null, mixed $default = null): mixed
    {
        $type = business_type() ?? config('business.default', 'clinic');
        $config = config("business.types.{$type}");

        if ($key === null) {
            return $config;
        }

        return data_get($config, $key, $default);
    }
}

if (! function_exists('business_label')) {
    /**
     * Get a localized label for the current business type.
     *
     * @param  string  $key  The label key (e.g., 'staff_label', 'service_label')
     */
    function business_label(string $key): string
    {
        $locale = app()->getLocale();
        $config = business_config();

        if (! $config) {
            return $key;
        }

        // Check for locale-specific key first (e.g., 'name_en')
        $localeKey = $key.'_'.$locale;
        if (isset($config[$localeKey])) {
            return $config[$localeKey];
        }

        // Fall back to default key
        return $config[$key] ?? $key;
    }
}

if (! function_exists('business_theme')) {
    /**
     * Get theme configuration for the current business type.
     *
     * @param  string|null  $key  Specific theme key (e.g., 'primary', 'button')
     */
    function business_theme(?string $key = null): mixed
    {
        $theme = business_config('theme', []);
        $store = current_store();

        if ($store && is_array($store->theme_custom)) {
            $theme = array_merge($theme, $store->theme_custom);
        }

        if ($key === null) {
            return $theme;
        }

        return $theme[$key] ?? null;
    }
}

if (! function_exists('business_profile_fields')) {
    /**
     * Get customer profile fields configuration for the current business type.
     */
    function business_profile_fields(): array
    {
        return business_config('profile_fields', []);
    }
}

if (! function_exists('business_profile_options')) {
    /**
     * Get profile field options with localized labels.
     *
     * @param  string  $field  The field name ('type' or 'concerns')
     */
    function business_profile_options(string $field): array
    {
        $fields = business_profile_fields();
        $locale = app()->getLocale();

        if (! isset($fields[$field]['options'])) {
            return [];
        }

        $options = [];
        foreach ($fields[$field]['options'] as $key => $labels) {
            $options[$key] = $labels[$locale] ?? $labels['id'] ?? $key;
        }

        return $options;
    }
}

if (! function_exists('clear_business_cache')) {
    /**
     * Clear cached business settings.
     * Call this when settings are updated.
     */
    function clear_business_cache(): void
    {
        Cache::forget('setup_completed');
        Cache::forget('business_type');
    }
}

if (! function_exists('staff_role_label')) {
    /**
     * Get the staff role label based on business type.
     * Maps 'beautician' role to appropriate label (Hairstylist, Barber, etc.)
     *
     * @param  string  $role  The role key
     * @param  bool  $plural  Whether to return plural form
     */
    function staff_role_label(string $role, bool $plural = false): string
    {
        // Owner and Admin are universal
        if (in_array($role, ['owner', 'admin'])) {
            return __("staff.role_{$role}");
        }

        // For beautician/staff role, use business-specific label
        if ($role === 'beautician') {
            $label = business_config($plural ? 'staff_label_plural' : 'staff_label');

            return $label ?? __('staff.role_beautician');
        }

        return __("staff.role_{$role}") ?? ucfirst($role);
    }
}

if (! function_exists('business_staff_label')) {
    /**
     * Get the staff label for the current business type.
     *
     * @param  bool  $plural  Whether to return plural form
     */
    function business_staff_label(bool $plural = false): string
    {
        $key = $plural ? 'staff_label_plural' : 'staff_label';

        return business_config($key) ?? __('staff.role_beautician');
    }
}

if (! function_exists('has_feature')) {
    /**
     * Check if a feature is enabled for the current business type.
     *
     * @param  string  $feature  The feature key (e.g., 'treatment_records', 'packages')
     */
    function has_feature(string $feature): bool
    {
        $features = business_features();

        return (bool) ($features[$feature] ?? false);
    }
}

if (! function_exists('business_features')) {
    /**
     * Get all features configuration for the current business type.
     */
    function business_features(): array
    {
        $features = business_config('features', []);
        $store = current_store();

        if ($store && $store->business_type !== 'gym') {
            $features['packages'] = false;
            $features['customer_packages'] = false;
            $features['attendance'] = false;
        }

        if ($store && is_array($store->feature_overrides)) {
            foreach ($store->feature_overrides as $feature => $enabled) {
                $features[$feature] = (bool) $enabled;
            }
        }

        return $features;
    }
}

if (! function_exists('customer_portal_plan')) {
    /**
     * Get the customer portal plan for the current store.
     */
    function customer_portal_plan(): string
    {
        $store = current_store();

        if ($store && $store->customer_portal_plan) {
            return $store->customer_portal_plan;
        }

        return 'premium';
    }
}

if (! function_exists('customer_portal_premium_eligible_features')) {
    /**
     * Features that can be marked as premium in the customer portal.
     *
     * @return array<int, string>
     */
    function customer_portal_premium_eligible_features(): array
    {
        $features = array_keys(business_features());

        if (! in_array('transactions', $features, true)) {
            $features[] = 'transactions';
        }

        return $features;
    }
}

if (! function_exists('customer_portal_premium_features')) {
    /**
     * Get premium feature list for the current store.
     *
     * @return array<int, string>
     */
    function customer_portal_premium_features(): array
    {
        $store = current_store();
        $eligible = customer_portal_premium_eligible_features();

        if ($store && is_array($store->portal_premium_features)) {
            return array_values(array_filter(
                $store->portal_premium_features,
                fn ($feature) => in_array($feature, $eligible, true)
            ));
        }

        return $eligible;
    }
}

if (! function_exists('customer_portal_feature_is_premium')) {
    /**
     * Determine if a feature is marked as premium.
     */
    function customer_portal_feature_is_premium(string $feature): bool
    {
        return in_array($feature, customer_portal_premium_features(), true);
    }
}

if (! function_exists('customer_portal_feature_locked')) {
    /**
     * Determine if a feature should be locked for basic portal plan.
     */
    function customer_portal_feature_locked(string $feature): bool
    {
        return false;
    }
}

if (! function_exists('customer_portal_is_premium')) {
    /**
     * Determine if the current store uses premium customer portal.
     */
    function customer_portal_is_premium(): bool
    {
        return customer_portal_plan() === 'premium';
    }
}

if (! function_exists('landing_text')) {
    /**
     * Get landing page text based on business type.
     * Falls back to default translation if business-specific not found.
     *
     * @param  string  $key  Translation key (e.g., 'hero_badge')
     * @param  array  $replace  Replacement values
     */
    function landing_text(string $key, array $replace = []): string
    {
        $type = business_type() ?? 'clinic';

        // Try business-specific key first (e.g., landing.salon.hero_badge)
        $businessKey = "landing.{$type}.{$key}";
        $translation = __($businessKey, $replace);

        // If business-specific not found, fall back to default
        if ($translation === $businessKey) {
            return __("landing.{$key}", $replace);
        }

        return $translation;
    }
}
