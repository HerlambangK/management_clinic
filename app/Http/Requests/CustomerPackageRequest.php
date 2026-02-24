<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerPackageRequest extends FormRequest
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
        $packageRule = Rule::exists('packages', 'id');

        if ($store) {
            $customerRule = $customerRule->where('store_id', $store->id);
            $packageRule = $packageRule->where('store_id', $store->id);
        }

        return [
            'customer_id' => ['required', $customerRule],
            'package_id' => ['required', $packageRule],
            'price_paid' => ['required', 'numeric', 'min:0'],
            'purchased_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
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

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_id.required' => 'Customer harus dipilih.',
            'customer_id.exists' => 'Customer tidak ditemukan.',
            'package_id.required' => 'Paket harus dipilih.',
            'package_id.exists' => 'Paket tidak ditemukan.',
            'price_paid.required' => 'Harga yang dibayar harus diisi.',
            'purchased_at.required' => 'Tanggal pembelian harus diisi.',
        ];
    }
}
