<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'birthdate' => $this->birthdate?->format('Y-m-d'),
            'age' => $this->age,
            'gender' => $this->gender,
            'address' => $this->address,
            'skin_type' => $this->skin_type,
            'skin_concerns' => $this->skin_concerns ?? [],
            'allergies' => $this->allergies,
            'notes' => $this->notes,
            'total_visits' => $this->total_visits,
            'total_spent' => (float) $this->total_spent,
            'formatted_total_spent' => $this->formatted_total_spent,
            'last_visit' => $this->last_visit?->format('Y-m-d'),
            // Loyalty fields
            'loyalty_points' => $this->loyalty_points ?? 0,
            'lifetime_points' => $this->lifetime_points ?? 0,
            'loyalty_tier' => $this->loyalty_tier ?? 'bronze',
            'loyalty_tier_label' => $this->loyalty_tier_label,
            // Referral fields
            'referral_code' => $this->referral_code,
            'referred_by_id' => $this->referred_by_id,
            'referrer' => new CustomerResource($this->whenLoaded('referrer')),
            'referral_stats' => $this->when($request->get('with_referral_stats'), fn () => $this->referral_stats),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
