<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'invoice_number' => $this->invoice_number,
            'customer_id' => $this->customer_id,
            'appointment_id' => $this->appointment_id,
            'cashier_id' => $this->cashier_id,
            'subtotal' => (float) $this->subtotal,
            'formatted_subtotal' => $this->formatted_subtotal,
            'discount_amount' => (float) $this->discount_amount,
            'formatted_discount_amount' => $this->formatted_discount_amount,
            'discount_type' => $this->discount_type,
            'tax_amount' => (float) $this->tax_amount,
            'total_amount' => (float) $this->total_amount,
            'formatted_total_amount' => $this->formatted_total_amount,
            'paid_amount' => (float) $this->paid_amount,
            'formatted_paid_amount' => $this->formatted_paid_amount,
            'change_amount' => (float) $this->change_amount,
            'outstanding_amount' => (float) $this->outstanding_amount,
            'formatted_outstanding_amount' => $this->formatted_outstanding_amount,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'is_paid' => $this->is_paid,
            'notes' => $this->notes,
            'paid_at' => $this->paid_at?->toISOString(),
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'appointment' => new AppointmentResource($this->whenLoaded('appointment')),
            'cashier' => new UserResource($this->whenLoaded('cashier')),
            'items' => TransactionItemResource::collection($this->whenLoaded('items')),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
