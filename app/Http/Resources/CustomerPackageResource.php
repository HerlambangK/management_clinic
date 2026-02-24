<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerPackageResource extends JsonResource
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
            'customer_id' => $this->customer_id,
            'package_id' => $this->package_id,
            'sold_by' => $this->sold_by,
            'price_paid' => (float) $this->price_paid,
            'formatted_price_paid' => $this->formatted_price_paid,
            'sessions_total' => $this->sessions_total,
            'sessions_used' => $this->sessions_used,
            'sessions_remaining' => $this->sessions_remaining,
            'usage_percentage' => $this->usage_percentage,
            'purchased_at' => $this->purchased_at?->format('Y-m-d'),
            'expires_at' => $this->expires_at?->format('Y-m-d'),
            'days_remaining' => $this->days_remaining,
            'is_expired' => $this->is_expired,
            'is_usable' => $this->is_usable,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'notes' => $this->notes,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'package' => new PackageResource($this->whenLoaded('package')),
            'seller' => new UserResource($this->whenLoaded('seller')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
