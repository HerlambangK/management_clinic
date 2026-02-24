<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
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
        $appointmentRule = Rule::exists('appointments', 'id');
        $serviceRule = Rule::exists('services', 'id');
        $packageRule = Rule::exists('packages', 'id');
        $productRule = Rule::exists('products', 'id');
        $customerPackageRule = Rule::exists('customer_packages', 'id');

        if ($store) {
            $customerRule = $customerRule->where('store_id', $store->id);
            $appointmentRule = $appointmentRule->where('store_id', $store->id);
            $serviceRule = $serviceRule->where('store_id', $store->id);
            $packageRule = $packageRule->where('store_id', $store->id);
            $productRule = $productRule->where('store_id', $store->id);
            $customerPackageRule = $customerPackageRule->where('store_id', $store->id);
        }

        return [
            'customer_id' => ['required', $customerRule],
            'appointment_id' => ['nullable', $appointmentRule],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_type' => ['required', 'in:service,package,product,other'],
            'items.*.service_id' => ['nullable', $serviceRule],
            'items.*.package_id' => ['nullable', $packageRule],
            'items.*.product_id' => ['nullable', $productRule],
            'items.*.customer_package_id' => ['nullable', $customerPackageRule],
            'items.*.item_name' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.discount' => ['nullable', 'numeric', 'min:0'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'discount_type' => ['nullable', 'string', 'max:50'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],
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
            'items.required' => 'Minimal 1 item harus ditambahkan.',
            'items.min' => 'Minimal 1 item harus ditambahkan.',
            'items.*.item_name.required' => 'Nama item harus diisi.',
            'items.*.quantity.required' => 'Jumlah harus diisi.',
            'items.*.unit_price.required' => 'Harga harus diisi.',
        ];
    }
}
