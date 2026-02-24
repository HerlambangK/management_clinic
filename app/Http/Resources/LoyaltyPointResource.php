<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoyaltyPointResource extends JsonResource
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
            'transaction_id' => $this->transaction_id,
            'type' => $this->type,
            'type_label' => $this->type_label,
            'points' => $this->points,
            'balance_after' => $this->balance_after,
            'description' => $this->description,
            'expires_at' => $this->expires_at?->format('Y-m-d'),
            'is_earn' => $this->is_earn,
            'is_redeem' => $this->is_redeem,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'transaction' => new TransactionResource($this->whenLoaded('transaction')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
