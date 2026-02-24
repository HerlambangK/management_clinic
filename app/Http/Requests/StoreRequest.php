<?php

namespace App\Http\Requests;

use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user && $user->hasRole(['owner', 'admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $businessTypes = array_keys(config('business.types', []));

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'business_type' => ['required', 'string', Rule::in($businessTypes)],
        ];

        if ($this->user()?->isAdmin()) {
            $rules['owner_id'] = ['required', 'exists:users,id'];
            $rules['is_active'] = ['nullable', 'boolean'];
            $rules['is_approved'] = ['nullable', 'boolean'];
        }

        return $rules;
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $businessType = $this->input('business_type');
            $ownerId = $this->resolveOwnerId();

            if (! $businessType || ! $ownerId) {
                return;
            }

            $existingType = $this->existingOwnerBusinessType($ownerId);

            if ($existingType && $existingType !== $businessType) {
                $validator->errors()->add(
                    'business_type',
                    "Owner ini sudah memiliki tipe bisnis {$existingType}."
                );
            }

            $owner = User::query()->find($ownerId);

            if ($owner?->business_type && $owner->business_type !== $businessType) {
                $validator->errors()->add(
                    'business_type',
                    "Owner ini hanya boleh menggunakan tipe bisnis {$owner->business_type}."
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama store wajib diisi.',
            'business_type.required' => 'Tipe bisnis wajib dipilih.',
            'business_type.in' => 'Tipe bisnis tidak valid.',
            'owner_id.required' => 'Owner wajib dipilih.',
            'owner_id.exists' => 'Owner tidak ditemukan.',
        ];
    }

    private function resolveOwnerId(): ?int
    {
        $user = $this->user();

        if (! $user) {
            return null;
        }

        if ($user->isAdmin()) {
            return $this->input('owner_id') ? (int) $this->input('owner_id') : null;
        }

        return $user->id;
    }

    private function existingOwnerBusinessType(int $ownerId): ?string
    {
        $query = Store::query()->where('owner_id', $ownerId);
        $currentStore = $this->route('store');

        if ($currentStore instanceof Store) {
            $query->where('id', '!=', $currentStore->id);
        }

        return $query->orderBy('id')->value('business_type');
    }
}
