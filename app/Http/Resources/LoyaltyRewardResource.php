<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoyaltyRewardResource extends JsonResource
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
            'points_required' => $this->points_required,
            'reward_type' => $this->reward_type,
            'reward_type_label' => $this->reward_type_label,
            'reward_value' => (float) $this->reward_value,
            'formatted_reward_value' => $this->formatted_reward_value,
            'service_id' => $this->service_id,
            'product_id' => $this->product_id,
            'stock' => $this->stock,
            'max_per_customer' => $this->max_per_customer,
            'valid_from' => $this->valid_from?->format('Y-m-d'),
            'valid_until' => $this->valid_until?->format('Y-m-d'),
            'is_active' => $this->is_active,
            'is_available' => $this->is_available,
            'sort_order' => $this->sort_order,
            'service' => new ServiceResource($this->whenLoaded('service')),
            'product' => new ProductResource($this->whenLoaded('product')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
