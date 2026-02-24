<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'business_type',
        'owner_id',
        'is_active',
        'is_approved',
        'approved_by',
        'approved_at',
        'theme_palette',
        'theme_custom',
        'feature_overrides',
        'customer_portal_plan',
        'portal_premium_features',
        'attendance_token',
        'attendance_token_generated_at',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_approved' => 'boolean',
            'approved_at' => 'datetime',
            'theme_custom' => 'array',
            'feature_overrides' => 'array',
            'portal_premium_features' => 'array',
            'attendance_token_generated_at' => 'datetime',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Store $store): void {
            if (! $store->attendance_token) {
                $store->attendance_token = Str::upper(Str::random(10));
            }

            if (! $store->attendance_token_generated_at) {
                $store->attendance_token_generated_at = now();
            }

            if (! $store->customer_portal_plan) {
                $store->customer_portal_plan = 'premium';
            }
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}
