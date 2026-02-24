<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentRecordResource extends JsonResource
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
            'appointment_id' => $this->appointment_id,
            'customer_id' => $this->customer_id,
            'staff_id' => $this->staff_id,
            'notes' => $this->notes,
            'products_used' => $this->products_used ?? [],
            'before_photo' => $this->before_photo,
            'before_photo_url' => $this->before_photo_url,
            'after_photo' => $this->after_photo,
            'after_photo_url' => $this->after_photo_url,
            'recommendations' => $this->recommendations,
            'follow_up_date' => $this->follow_up_date?->format('Y-m-d'),
            'appointment' => new AppointmentResource($this->whenLoaded('appointment')),
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'staff' => new UserResource($this->whenLoaded('staff')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
