<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Customer extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use BelongsToStore, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'store_id',
        'name',
        'phone',
        'email',
        'password',
        'birthdate',
        'gender',
        'address',
        'skin_type',
        'skin_concerns',
        'allergies',
        'notes',
        'total_visits',
        'total_spent',
        'last_visit',
        'loyalty_points',
        'lifetime_points',
        'loyalty_tier',
        'referral_code',
        'referred_by_id',
        'referral_rewarded_at',
        'otp_code',
        'otp_expires_at',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
            'skin_concerns' => 'array',
            'total_visits' => 'integer',
            'total_spent' => 'decimal:2',
            'last_visit' => 'date',
            'password' => 'hashed',
            'referral_rewarded_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'email_verified_at' => 'datetime',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($customer) {
            if (empty($customer->referral_code)) {
                $customer->referral_code = self::generateReferralCode();
            }
        });
    }

    /**
     * Generate unique referral code
     */
    public static function generateReferralCode(): string
    {
        $prefix = config('referral.code_prefix', 'REF');

        do {
            $code = $prefix.'-'.strtoupper(Str::random(8));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * Generate OTP code for login
     */
    public function generateOtp(): string
    {
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $this->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        return $otp;
    }

    /**
     * Verify OTP code
     */
    public function verifyOtp(string $code): bool
    {
        if ($this->otp_code !== $code) {
            return false;
        }

        if ($this->otp_expires_at && $this->otp_expires_at->isPast()) {
            return false;
        }

        $this->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'email_verified_at' => $this->email_verified_at ?? now(),
        ]);

        return true;
    }

    /**
     * Check if customer has email verified
     */
    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(MemberAttendance::class);
    }

    public function treatmentRecords(): HasMany
    {
        return $this->hasMany(TreatmentRecord::class);
    }

    public function packages(): HasMany
    {
        return $this->hasMany(CustomerPackage::class);
    }

    public function activePackages(): HasMany
    {
        return $this->hasMany(CustomerPackage::class)->usable();
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function loyaltyPoints(): HasMany
    {
        return $this->hasMany(LoyaltyPoint::class);
    }

    public function loyaltyRedemptions(): HasMany
    {
        return $this->hasMany(LoyaltyRedemption::class);
    }

    public function pendingRedemptions(): HasMany
    {
        return $this->hasMany(LoyaltyRedemption::class)->valid();
    }

    public function hasActiveMembership(): bool
    {
        return $this->activePackages()->exists();
    }

    /**
     * The customer who referred this customer
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'referred_by_id');
    }

    /**
     * Customers referred by this customer
     */
    public function referrals(): HasMany
    {
        return $this->hasMany(Customer::class, 'referred_by_id');
    }

    /**
     * Referral logs where this customer is the referrer
     */
    public function referralLogs(): HasMany
    {
        return $this->hasMany(ReferralLog::class, 'referrer_id');
    }

    /**
     * Get referral statistics for this customer
     *
     * @return array<string, int>
     */
    public function getReferralStatsAttribute(): array
    {
        $referrals = $this->referrals();

        return [
            'total_referrals' => $referrals->count(),
            'pending_referrals' => $referrals->whereNull('referral_rewarded_at')->count(),
            'rewarded_referrals' => $referrals->whereNotNull('referral_rewarded_at')->count(),
            'total_points_earned' => $this->referralLogs()->rewarded()->sum('referrer_points'),
        ];
    }

    /**
     * Check if this customer was referred and referral reward is pending
     */
    public function hasUnrewardedReferral(): bool
    {
        return $this->referred_by_id !== null && $this->referral_rewarded_at === null;
    }

    public function getAgeAttribute(): ?int
    {
        if (! $this->birthdate) {
            return null;
        }

        return $this->birthdate->age;
    }

    public function getFormattedTotalSpentAttribute(): string
    {
        return 'Rp '.number_format($this->total_spent, 0, ',', '.');
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });
    }

    /**
     * Add loyalty points to customer
     */
    public function addLoyaltyPoints(int $points, string $type = 'earn', ?Transaction $transaction = null, ?string $description = null, ?string $expiresAt = null): LoyaltyPoint
    {
        $newBalance = $this->loyalty_points + $points;

        $loyaltyPoint = $this->loyaltyPoints()->create([
            'transaction_id' => $transaction?->id,
            'type' => $type,
            'points' => $points,
            'balance_after' => $newBalance,
            'description' => $description,
            'expires_at' => $expiresAt,
        ]);

        $this->update([
            'loyalty_points' => $newBalance,
            'lifetime_points' => $this->lifetime_points + ($type === 'earn' ? $points : 0),
        ]);

        $this->updateLoyaltyTier();

        return $loyaltyPoint;
    }

    /**
     * Deduct loyalty points from customer
     */
    public function deductLoyaltyPoints(int $points, string $type = 'redeem', ?Transaction $transaction = null, ?string $description = null): ?LoyaltyPoint
    {
        if ($this->loyalty_points < $points) {
            return null;
        }

        $newBalance = $this->loyalty_points - $points;

        $loyaltyPoint = $this->loyaltyPoints()->create([
            'transaction_id' => $transaction?->id,
            'type' => $type,
            'points' => -$points,
            'balance_after' => $newBalance,
            'description' => $description,
        ]);

        $this->update(['loyalty_points' => $newBalance]);

        return $loyaltyPoint;
    }

    /**
     * Calculate points earned from a transaction amount
     */
    public static function calculatePointsFromAmount(float $amount): int
    {
        // 1 point per 10,000 IDR spent
        $pointsPerAmount = (int) config('loyalty.points_per_amount', 10000);

        return (int) floor($amount / $pointsPerAmount);
    }

    /**
     * Update customer loyalty tier based on lifetime points
     */
    public function updateLoyaltyTier(): void
    {
        $tiers = config('loyalty.tiers', [
            'bronze' => 0,
            'silver' => 1000,
            'gold' => 5000,
            'platinum' => 10000,
        ]);

        $newTier = 'bronze';

        foreach ($tiers as $tier => $minPoints) {
            if ($this->lifetime_points >= $minPoints) {
                $newTier = $tier;
            }
        }

        if ($this->loyalty_tier !== $newTier) {
            $this->update(['loyalty_tier' => $newTier]);
        }
    }

    /**
     * Get loyalty tier label
     */
    public function getLoyaltyTierLabelAttribute(): string
    {
        $labels = [
            'bronze' => 'Bronze',
            'silver' => 'Silver',
            'gold' => 'Gold',
            'platinum' => 'Platinum',
        ];

        return $labels[$this->loyalty_tier] ?? 'Bronze';
    }

    /**
     * Get loyalty tier color class
     */
    public function getLoyaltyTierColorAttribute(): string
    {
        $colors = [
            'bronze' => 'bg-amber-100 text-amber-700',
            'silver' => 'bg-gray-100 text-gray-700',
            'gold' => 'bg-yellow-100 text-yellow-700',
            'platinum' => 'bg-purple-100 text-purple-700',
        ];

        return $colors[$this->loyalty_tier] ?? 'bg-amber-100 text-amber-700';
    }
}
