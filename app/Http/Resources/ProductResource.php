<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'sku' => $this->sku,
            'description' => $this->description,
            'price' => (float) $this->price,
            'formatted_price' => $this->formatted_price,
            'cost_price' => $this->cost_price ? (float) $this->cost_price : null,
            'formatted_cost_price' => $this->formatted_cost_price,
            'stock' => $this->stock,
            'min_stock' => $this->min_stock,
            'unit' => $this->unit,
            'image' => $this->image,
            'image_url' => $this->image ? asset('storage/'.$this->image) : null,
            'is_active' => $this->is_active,
            'track_stock' => $this->track_stock,
            'is_low_stock' => $this->is_low_stock,
            'is_out_of_stock' => $this->is_out_of_stock,
            'category' => new ProductCategoryResource($this->whenLoaded('category')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
