<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;
        $store = current_store();
        $categoryRule = Rule::exists('product_categories', 'id');

        if ($store) {
            $categoryRule = $categoryRule->where('store_id', $store->id);
        }

        return [
            'category_id' => ['nullable', $categoryRule],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($productId)],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['boolean'],
            'track_stock' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('product.name')]),
            'price.required' => __('validation.required', ['attribute' => __('product.price')]),
            'price.min' => __('validation.min.numeric', ['attribute' => __('product.price'), 'min' => 0]),
            'sku.unique' => __('product.sku_exists'),
            'image.image' => __('validation.image', ['attribute' => __('product.image')]),
            'image.max' => __('validation.max.file', ['attribute' => __('product.image'), 'max' => 2048]),
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
}
