<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerPackage extends Model
{
    use BelongsToStore, HasFactory, SoftDeletes;

    public const STATUSES = [
        'active' => 'Aktif',
        'completed' => 'Selesai',
        'expired' => 'Kadaluarsa',
        'cancelled' => 'Dibatalkan',
    ];

    protected $fillable = [
        'store_id',
        'customer_id',
        'package_id',
        'sold_by',
        'price_paid',
        'sessions_total',
        'sessions_used',
        'purchased_at',
        'expires_at',
        'status',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price_paid' => 'decimal:2',
            'purchased_at' => 'date',
            'expires_at' => 'date',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sold_by');
    }

    public function usages(): HasMany
    {
        return $this->hasMany(PackageUsage::class);
    }

    public function getSessionsRemainingAttribute(): int
    {
        return max(0, $this->sessions_total - $this->sessions_used);
    }

    public function getUsagePercentageAttribute(): float
    {
        if ($this->sessions_total <= 0) {
            return 0;
        }

        return round(($this->sessions_used / $this->sessions_total) * 100, 1);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getFormattedPricePaidAttribute(): string
    {
        return 'Rp '.number_format($this->price_paid, 0, ',', '.');
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at->isPast();
    }

    public function getIsUsableAttribute(): bool
    {
        return $this->status === 'active'
            && ! $this->is_expired
            && $this->sessions_remaining > 0;
    }

    public function getDaysRemainingAttribute(): int
    {
        if ($this->expires_at->isPast()) {
            return 0;
        }

        return now()->diffInDays($this->expires_at);
    }

    public function useSession(?int $appointmentId = null, ?int $usedBy = null, ?string $notes = null): PackageUsage
    {
        $usage = $this->usages()->create([
            'appointment_id' => $appointmentId,
            'used_by' => $usedBy ?? auth()->id(),
            'used_at' => today(),
            'notes' => $notes,
        ]);

        $this->increment('sessions_used');

        if ($this->sessions_used >= $this->sessions_total) {
            $this->update(['status' => 'completed']);
        }

        return $usage;
    }

    public function checkAndUpdateStatus(): void
    {
        if ($this->status === 'active') {
            if ($this->sessions_used >= $this->sessions_total) {
                $this->update(['status' => 'completed']);
            } elseif ($this->expires_at->isPast()) {
                $this->update(['status' => 'expired']);
            }
        }
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUsable($query)
    {
        return $query->where('status', 'active')
            ->where('expires_at', '>=', today())
            ->whereRaw('sessions_used < sessions_total');
    }

    public function scopeForCustomer($query, int $customerId)
    {
        return $query->where('customer_id', $customerId);
    }
}
