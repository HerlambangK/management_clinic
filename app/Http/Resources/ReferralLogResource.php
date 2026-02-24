<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReferralLogResource extends JsonResource
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
            'referrer_id' => $this->referrer_id,
            'referee_id' => $this->referee_id,
            'referrer_points' => $this->referrer_points,
            'referee_points' => $this->referee_points,
            'transaction_id' => $this->transaction_id,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'rewarded_at' => $this->rewarded_at?->toISOString(),
            'referrer' => new CustomerResource($this->whenLoaded('referrer')),
            'referee' => new CustomerResource($this->whenLoaded('referee')),
            'transaction' => new TransactionResource($this->whenLoaded('transaction')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
