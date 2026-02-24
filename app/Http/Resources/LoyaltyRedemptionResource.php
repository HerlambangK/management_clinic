<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoyaltyRedemptionResource extends JsonResource
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
            'loyalty_reward_id' => $this->loyalty_reward_id,
            'transaction_id' => $this->transaction_id,
            'points_used' => $this->points_used,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'code' => $this->code,
            'valid_until' => $this->valid_until?->format('Y-m-d'),
            'used_at' => $this->used_at?->toISOString(),
            'is_valid' => $this->is_valid,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'reward' => new LoyaltyRewardResource($this->whenLoaded('reward')),
            'transaction' => new TransactionResource($this->whenLoaded('transaction')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
