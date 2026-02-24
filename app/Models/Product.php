<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use BelongsToStore, HasFactory, SoftDeletes;

    protected $fillable = [
        'store_id',
        'category_id',
        'name',
        'sku',
        'description',
        'price',
        'cost_price',
        'stock',
        'min_stock',
        'unit',
        'image',
        'is_active',
        'track_stock',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'is_active' => 'boolean',
            'track_stock' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp '.number_format($this->price, 0, ',', '.');
    }

    public function getFormattedCostPriceAttribute(): string
    {
        return $this->cost_price ? 'Rp '.number_format($this->cost_price, 0, ',', '.') : '-';
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->track_stock && $this->stock <= $this->min_stock;
    }

    public function getIsOutOfStockAttribute(): bool
    {
        return $this->track_stock && $this->stock <= 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->where('track_stock', false)
                ->orWhere('stock', '>', 0);
        });
    }

    public function scopeLowStock($query)
    {
        return $query->where('track_stock', true)
            ->whereColumn('stock', '<=', 'min_stock');
    }

    public function decreaseStock(int $quantity): bool
    {
        if (! $this->track_stock) {
            return true;
        }

        if ($this->stock < $quantity) {
            return false;
        }

        $this->decrement('stock', $quantity);

        return true;
    }

    public function increaseStock(int $quantity): void
    {
        if ($this->track_stock) {
            $this->increment('stock', $quantity);
        }
    }
}
