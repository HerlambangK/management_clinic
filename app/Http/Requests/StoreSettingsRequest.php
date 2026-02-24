<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user && $user->hasRole(['owner', 'admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $paletteKeys = array_keys(brand_palettes());
        $paletteKeys[] = '';

        return [
            'features' => ['nullable', 'array'],
            'features.*' => ['nullable', 'boolean'],
            'premium_features' => ['nullable', 'array'],
            'premium_features.*' => ['nullable', 'boolean'],
            'customer_portal_plan' => ['required', 'string', Rule::in(['basic', 'premium'])],
            'theme_palette' => ['nullable', 'string', Rule::in($paletteKeys)],
            'theme_custom_primary' => ['nullable', 'regex:/^(#([0-9a-fA-F]{3}|[0-9a-fA-F]{6}))?$/'],
            'theme_custom_primary_hover' => ['nullable', 'regex:/^(#([0-9a-fA-F]{3}|[0-9a-fA-F]{6}))?$/'],
            'theme_custom_primary_light' => ['nullable', 'regex:/^(#([0-9a-fA-F]{3}|[0-9a-fA-F]{6}))?$/'],
            'store_latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'store_longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_portal_plan.required' => 'Pilih jenis menu customer.',
            'customer_portal_plan.in' => 'Jenis menu customer tidak valid.',
            'theme_palette.in' => 'Palette tidak valid.',
            'theme_custom_primary.regex' => 'Warna primary harus format hex.',
            'theme_custom_primary_hover.regex' => 'Warna primary hover harus format hex.',
            'theme_custom_primary_light.regex' => 'Warna primary light harus format hex.',
            'store_latitude.between' => 'Latitude tidak valid.',
            'store_longitude.between' => 'Longitude tidak valid.',
        ];
    }
}
