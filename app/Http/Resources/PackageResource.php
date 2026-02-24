<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
            'description' => $this->description,
            'service_id' => $this->service_id,
            'total_sessions' => $this->total_sessions,
            'original_price' => (float) $this->original_price,
            'formatted_original_price' => $this->formatted_original_price,
            'package_price' => (float) $this->package_price,
            'formatted_package_price' => $this->formatted_package_price,
            'discount_percentage' => $this->discount_percentage,
            'savings' => (float) $this->savings,
            'formatted_savings' => $this->formatted_savings,
            'price_per_session' => (float) $this->price_per_session,
            'formatted_price_per_session' => $this->formatted_price_per_session,
            'validity_days' => $this->validity_days,
            'is_active' => $this->is_active,
            'service' => new ServiceResource($this->whenLoaded('service')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
