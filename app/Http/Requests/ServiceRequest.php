<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        $store = current_store();
        $categoryRule = Rule::exists('service_categories', 'id');

        if ($store) {
            $categoryRule = $categoryRule->where('store_id', $store->id);
        }

        return [
            'category_id' => ['nullable', $categoryRule],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:480'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
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
            'name.required' => 'Nama layanan wajib diisi.',
            'duration_minutes.required' => 'Durasi wajib diisi.',
            'duration_minutes.min' => 'Durasi minimal 5 menit.',
            'duration_minutes.max' => 'Durasi maksimal 480 menit (8 jam).',
            'price.required' => 'Harga wajib diisi.',
            'price.min' => 'Harga tidak boleh negatif.',
            'image.image' => 'File harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
