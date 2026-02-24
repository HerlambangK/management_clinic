<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoyaltyRewardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'points_required' => ['required', 'integer', 'min:1'],
            'reward_type' => ['required', 'in:discount_percent,discount_amount,free_service,free_product,other'],
            'reward_value' => ['nullable', 'numeric', 'min:0'],
            'service_id' => ['nullable', 'exists:services,id'],
            'product_id' => ['nullable', 'exists:products,id'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'max_per_customer' => ['nullable', 'integer', 'min:1'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('loyalty.reward_name')]),
            'points_required.required' => __('validation.required', ['attribute' => __('loyalty.points_required')]),
            'points_required.min' => __('validation.min.numeric', ['attribute' => __('loyalty.points_required'), 'min' => 1]),
            'reward_type.required' => __('validation.required', ['attribute' => __('loyalty.reward_type')]),
            'valid_until.after_or_equal' => __('loyalty.valid_until_after_from'),
        ];
    }
}
