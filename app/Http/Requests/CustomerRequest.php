<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
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
        $customerId = $this->route('customer')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^08[0-9]{8,13}$/',
                Rule::unique('customers', 'phone')->ignore($customerId),
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'birthdate' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'address' => ['nullable', 'string', 'max:500'],
            'skin_type' => ['nullable', Rule::in(['normal', 'oily', 'dry', 'combination', 'sensitive'])],
            'skin_concerns' => ['nullable', 'array'],
            'skin_concerns.*' => ['string', Rule::in([
                'acne', 'aging', 'pigmentation', 'dull', 'pores',
                'redness', 'dehydration', 'oily', 'sensitive', 'blackheads',
            ])],
            'allergies' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'referral_code' => ['nullable', 'string', 'max:20'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone.regex' => 'Nomor telepon harus dimulai dengan 08 dan terdiri dari 10-15 digit.',
            'phone.unique' => 'Nomor telepon sudah terdaftar.',
            'birthdate.before' => 'Tanggal lahir harus sebelum hari ini.',
        ];
    }
}
