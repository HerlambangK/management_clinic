<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberAttendance extends Model
{
    /** @use HasFactory<\Database\Factories\MemberAttendanceFactory> */
    use BelongsToStore, HasFactory;

    protected $fillable = [
        'store_id',
        'customer_id',
        'checked_in_at',
        'checked_out_at',
        'latitude',
        'longitude',
        'accuracy',
        'location_name',
        'checkout_latitude',
        'checkout_longitude',
        'checkout_accuracy',
        'checkout_location_name',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'checked_in_at' => 'datetime',
            'checked_out_at' => 'datetime',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'accuracy' => 'integer',
            'location_name' => 'string',
            'checkout_latitude' => 'decimal:7',
            'checkout_longitude' => 'decimal:7',
            'checkout_accuracy' => 'integer',
            'checkout_location_name' => 'string',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
