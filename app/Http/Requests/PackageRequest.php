<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $store = current_store();
        $serviceRule = Rule::exists('services', 'id');

        if ($store) {
            $serviceRule = $serviceRule->where('store_id', $store->id);
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'service_id' => ['nullable', $serviceRule],
            'total_sessions' => ['required', 'integer', 'min:1', 'max:100'],
            'original_price' => ['required', 'numeric', 'min:0'],
            'package_price' => ['required', 'numeric', 'min:0'],
            'validity_days' => ['required', 'integer', 'min:1', 'max:3650'],
            'is_active' => ['boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = $this->user();

            if ($user && $user->isAdmin() && ! current_store()) {
                $validator->errors()->add('name', __('store.select_store_first'));
            }
        });
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama paket harus diisi.',
            'total_sessions.required' => 'Jumlah sesi harus diisi.',
            'total_sessions.min' => 'Jumlah sesi minimal 1.',
            'original_price.required' => 'Harga normal harus diisi.',
            'package_price.required' => 'Harga paket harus diisi.',
            'validity_days.required' => 'Masa berlaku harus diisi.',
            'validity_days.min' => 'Masa berlaku minimal 1 hari.',
        ];
    }
}
