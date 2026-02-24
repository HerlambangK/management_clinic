<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoyaltyReward extends Model
{
    use BelongsToStore, HasFactory, SoftDeletes;

    public const REWARD_TYPES = [
        'discount_percent' => 'Diskon Persen',
        'discount_amount' => 'Diskon Nominal',
        'free_service' => 'Gratis Layanan',
        'free_product' => 'Gratis Produk',
        'other' => 'Lainnya',
    ];

    protected $fillable = [
        'store_id',
        'name',
        'description',
        'points_required',
        'reward_type',
        'reward_value',
        'service_id',
        'product_id',
        'stock',
        'max_per_customer',
        'valid_from',
        'valid_until',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'reward_value' => 'decimal:2',
            'is_active' => 'boolean',
            'valid_from' => 'date',
            'valid_until' => 'date',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(LoyaltyRedemption::class);
    }

    public function getRewardTypeLabelAttribute(): string
    {
        return self::REWARD_TYPES[$this->reward_type] ?? $this->reward_type;
    }

    public function getFormattedRewardValueAttribute(): string
    {
        if ($this->reward_type === 'discount_percent') {
            return $this->reward_value.'%';
        }

        if ($this->reward_type === 'discount_amount') {
            return 'Rp '.number_format($this->reward_value, 0, ',', '.');
        }

        return '-';
    }

    public function getIsAvailableAttribute(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->valid_from && $this->valid_from > now()) {
            return false;
        }

        if ($this->valid_until && $this->valid_until < now()) {
            return false;
        }

        if ($this->stock !== null && $this->stock <= 0) {
            return false;
        }

        return true;
    }

    public function canBeRedeemedBy(Customer $customer): bool
    {
        if (! $this->is_available) {
            return false;
        }

        if ($customer->loyalty_points < $this->points_required) {
            return false;
        }

        if ($this->max_per_customer) {
            $redemptionCount = $this->redemptions()
                ->where('customer_id', $customer->id)
                ->whereIn('status', ['pending', 'used'])
                ->count();

            if ($redemptionCount >= $this->max_per_customer) {
                return false;
            }
        }

        return true;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('valid_from')
                    ->orWhere('valid_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('stock')
                    ->orWhere('stock', '>', 0);
            });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('points_required');
    }
}
