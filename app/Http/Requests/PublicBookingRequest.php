<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public booking, no auth required
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'service_id' => ['required', 'exists:services,id'],
            'staff_id' => ['nullable', 'exists:users,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string', 'max:500'],
            'referral_code' => ['nullable', 'string', 'max:20'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => __('booking.validation.name_required'),
            'phone.required' => __('booking.validation.phone_required'),
            'email.email' => __('booking.validation.email_invalid'),
            'service_id.required' => __('booking.validation.service_required'),
            'service_id.exists' => __('booking.validation.service_invalid'),
            'appointment_date.required' => __('booking.validation.date_required'),
            'appointment_date.after_or_equal' => __('booking.validation.date_past'),
            'start_time.required' => __('booking.validation.time_required'),
        ];
    }
}
