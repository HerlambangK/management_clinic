<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralLog extends Model
{
    use BelongsToStore;

    public const STATUSES = [
        'pending' => 'Menunggu',
        'rewarded' => 'Diberikan',
        'cancelled' => 'Dibatalkan',
    ];

    protected $fillable = [
        'store_id',
        'referrer_id',
        'referee_id',
        'referrer_points',
        'referee_points',
        'transaction_id',
        'status',
        'rewarded_at',
    ];

    protected function casts(): array
    {
        return [
            'rewarded_at' => 'datetime',
        ];
    }

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'referrer_id');
    }

    public function referee(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'referee_id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function markAsRewarded(Transaction $transaction): void
    {
        $this->update([
            'status' => 'rewarded',
            'transaction_id' => $transaction->id,
            'rewarded_at' => now(),
        ]);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRewarded($query)
    {
        return $query->where('status', 'rewarded');
    }
}
