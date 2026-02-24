<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'category_id' => $this->category_id,
            'name' => $this->name,
            'description' => $this->description,
            'duration_minutes' => $this->duration_minutes,
            'formatted_duration' => $this->formatted_duration,
            'price' => (float) $this->price,
            'formatted_price' => $this->formatted_price,
            'image' => $this->image,
            'is_active' => $this->is_active,
            'category' => new ServiceCategoryResource($this->whenLoaded('category')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
