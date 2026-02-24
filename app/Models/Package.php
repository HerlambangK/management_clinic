<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use BelongsToStore, HasFactory, SoftDeletes;

    protected $fillable = [
        'store_id',
        'name',
        'description',
        'service_id',
        'total_sessions',
        'original_price',
        'package_price',
        'validity_days',
        'is_active',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'original_price' => 'decimal:2',
            'package_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function customerPackages(): HasMany
    {
        return $this->hasMany(CustomerPackage::class);
    }

    public function getDiscountPercentageAttribute(): float
    {
        if ($this->original_price <= 0) {
            return 0;
        }

        return round((($this->original_price - $this->package_price) / $this->original_price) * 100, 1);
    }

    public function getSavingsAttribute(): float
    {
        return $this->original_price - $this->package_price;
    }

    public function getFormattedPackagePriceAttribute(): string
    {
        return 'Rp '.number_format($this->package_price, 0, ',', '.');
    }

    public function getFormattedOriginalPriceAttribute(): string
    {
        return 'Rp '.number_format($this->original_price, 0, ',', '.');
    }

    public function getFormattedSavingsAttribute(): string
    {
        return 'Rp '.number_format($this->savings, 0, ',', '.');
    }

    public function getPricePerSessionAttribute(): float
    {
        if ($this->total_sessions <= 0) {
            return 0;
        }

        return $this->package_price / $this->total_sessions;
    }

    public function getFormattedPricePerSessionAttribute(): string
    {
        return 'Rp '.number_format($this->price_per_session, 0, ',', '.');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
