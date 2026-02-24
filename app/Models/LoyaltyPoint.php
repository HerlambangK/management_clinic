<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyPoint extends Model
{
    use BelongsToStore;

    public const TYPES = [
        'earn' => 'Dapat Poin',
        'redeem' => 'Tukar Poin',
        'expire' => 'Kadaluarsa',
        'adjust' => 'Penyesuaian',
    ];

    protected $fillable = [
        'store_id',
        'customer_id',
        'transaction_id',
        'type',
        'points',
        'balance_after',
        'description',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'date',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getIsEarnAttribute(): bool
    {
        return $this->type === 'earn';
    }

    public function getIsRedeemAttribute(): bool
    {
        return $this->type === 'redeem';
    }

    public function scopeEarned($query)
    {
        return $query->where('type', 'earn');
    }

    public function scopeRedeemed($query)
    {
        return $query->where('type', 'redeem');
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        });
    }
}
