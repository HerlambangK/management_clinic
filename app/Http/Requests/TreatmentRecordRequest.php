<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TreatmentRecordRequest extends FormRequest
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
        $appointmentRule = Rule::exists('appointments', 'id');

        if ($store) {
            $appointmentRule = $appointmentRule->where('store_id', $store->id);
        }

        $rules = [
            'notes' => ['nullable', 'string', 'max:5000'],
            'products_used' => ['nullable', 'array'],
            'products_used.*' => ['nullable', 'string', 'max:255'],
            'before_photos' => ['nullable', 'array', 'max:5'],
            'before_photos.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'after_photos' => ['nullable', 'array', 'max:5'],
            'after_photos.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'recommendations' => ['nullable', 'string', 'max:2000'],
            'follow_up_date' => ['nullable', 'date', 'after:today'],
        ];

        if ($this->isMethod('POST')) {
            $rules['appointment_id'] = ['required', $appointmentRule];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'appointment_id.required' => __('treatment.appointment_required'),
            'appointment_id.exists' => __('treatment.appointment_not_found'),
            'before_photos.max' => __('treatment.max_photos_5'),
            'before_photos.*.image' => __('treatment.invalid_file_type'),
            'before_photos.*.max' => __('treatment.file_too_large'),
            'after_photos.max' => __('treatment.max_photos_5'),
            'after_photos.*.image' => __('treatment.invalid_file_type'),
            'after_photos.*.max' => __('treatment.file_too_large'),
            'follow_up_date.after' => __('treatment.follow_up_after_today'),
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = $this->user();

            if ($user && $user->isAdmin() && ! current_store()) {
                $validator->errors()->add('appointment_id', __('store.select_store_first'));
            }
        });
    }
}
