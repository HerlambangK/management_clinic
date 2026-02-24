<?php

namespace App\Http\Resources;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'transaction_id' => $this->transaction_id,
            'amount' => (float) $this->amount,
            'payment_method' => $this->payment_method,
            'payment_method_label' => Transaction::PAYMENT_METHODS[$this->payment_method] ?? $this->payment_method,
            'reference_number' => $this->reference_number,
            'notes' => $this->notes,
            'received_by' => $this->received_by,
            'receiver' => new UserResource($this->whenLoaded('receiver')),
            'paid_at' => $this->paid_at?->toISOString(),
        ];
    }
}
