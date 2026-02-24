<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
            'service_id' => $this->service_id,
            'staff_id' => $this->staff_id,
            'customer_package_id' => $this->customer_package_id,
            'appointment_date' => $this->appointment_date?->format('Y-m-d'),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'status_color' => $this->status_color,
            'source' => $this->source,
            'source_label' => $this->source_label,
            'notes' => $this->notes,
            'cancelled_at' => $this->cancelled_at?->toISOString(),
            'cancelled_reason' => $this->cancelled_reason,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'service' => new ServiceResource($this->whenLoaded('service')),
            'staff' => new UserResource($this->whenLoaded('staff')),
            'treatment_record' => new TreatmentRecordResource($this->whenLoaded('treatmentRecord')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
