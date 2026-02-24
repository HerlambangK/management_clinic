<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageUsageResource extends JsonResource
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
            'customer_package_id' => $this->customer_package_id,
            'appointment_id' => $this->appointment_id,
            'used_by' => $this->used_by,
            'used_at' => $this->used_at?->format('Y-m-d'),
            'notes' => $this->notes,
            'used_by_staff' => new UserResource($this->whenLoaded('usedByStaff')),
            'appointment' => $this->whenLoaded('appointment'),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
