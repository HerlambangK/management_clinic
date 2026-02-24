<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AppointmentRequest extends FormRequest
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
        $customerRule = Rule::exists('customers', 'id');
        $serviceRule = Rule::exists('services', 'id');

        if ($store) {
            $customerRule = $customerRule->where('store_id', $store->id);
            $serviceRule = $serviceRule->where('store_id', $store->id);
        }

        return [
            'customer_id' => ['required', $customerRule],
            'service_id' => ['required', $serviceRule],
            'staff_id' => ['nullable', 'exists:users,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'source' => ['nullable', Rule::in(['walk_in', 'phone', 'whatsapp', 'online'])],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_id.required' => 'Customer harus dipilih.',
            'customer_id.exists' => 'Customer tidak ditemukan.',
            'service_id.required' => 'Layanan harus dipilih.',
            'service_id.exists' => 'Layanan tidak ditemukan.',
            'appointment_date.required' => 'Tanggal appointment harus diisi.',
            'appointment_date.after_or_equal' => 'Tanggal appointment tidak boleh di masa lalu.',
            'start_time.required' => 'Waktu mulai harus dipilih.',
            'start_time.date_format' => 'Format waktu tidak valid.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = $this->user();

            if ($user && $user->isAdmin() && ! current_store()) {
                $validator->errors()->add('customer_id', __('store.select_store_first'));
            }
        });
    }
}
