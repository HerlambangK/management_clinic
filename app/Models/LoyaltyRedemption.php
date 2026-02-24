<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class LoyaltyRedemption extends Model
{
    use BelongsToStore;

    public const STATUSES = [
        'pending' => 'Menunggu',
        'used' => 'Digunakan',
        'expired' => 'Kadaluarsa',
        'cancelled' => 'Dibatalkan',
    ];

    protected $fillable = [
        'store_id',
        'customer_id',
        'loyalty_reward_id',
        'transaction_id',
        'loyalty_point_id',
        'points_used',
        'status',
        'code',
        'valid_until',
        'used_at',
    ];

    protected function casts(): array
    {
        return [
            'valid_until' => 'date',
            'used_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($redemption) {
            if (empty($redemption->code)) {
                $redemption->code = self::generateCode();
            }
        });
    }

    public static function generateCode(): string
    {
        do {
            $code = 'RWD-'.strtoupper(Str::random(8));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function reward(): BelongsTo
    {
        return $this->belongsTo(LoyaltyReward::class, 'loyalty_reward_id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function loyaltyPoint(): BelongsTo
    {
        return $this->belongsTo(LoyaltyPoint::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getIsValidAttribute(): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }

        if ($this->valid_until && $this->valid_until < now()) {
            return false;
        }

        return true;
    }

    public function markAsUsed(?Transaction $transaction = null): void
    {
        $this->update([
            'status' => 'used',
            'transaction_id' => $transaction?->id,
            'used_at' => now(),
        ]);
    }

    public function markAsExpired(): void
    {
        $this->update(['status' => 'expired']);
    }

    public function cancel(): void
    {
        if ($this->status !== 'pending') {
            return;
        }

        // Refund points to customer
        $this->customer->addLoyaltyPoints(
            $this->points_used,
            'adjust',
            null,
            __('loyalty.points_refunded', ['reward' => $this->reward->name])
        );

        $this->update(['status' => 'cancelled']);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeUsed($query)
    {
        return $query->where('status', 'used');
    }

    public function scopeValid($query)
    {
        return $query->pending()
            ->where(function ($q) {
                $q->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', now());
            });
    }
}
