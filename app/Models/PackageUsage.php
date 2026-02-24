<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageUsage extends Model
{
    use BelongsToStore;

    protected $fillable = [
        'store_id',
        'customer_package_id',
        'appointment_id',
        'used_by',
        'used_at',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'used_at' => 'date',
        ];
    }

    public function customerPackage(): BelongsTo
    {
        return $this->belongsTo(CustomerPackage::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function usedByStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    public function treatmentRecord(): \Illuminate\Database\Eloquent\Relations\HasOneThrough
    {
        return $this->hasOneThrough(
            TreatmentRecord::class,
            Appointment::class,
            'id', // Foreign key on appointments table
            'appointment_id', // Foreign key on treatment_records table
            'appointment_id', // Local key on package_usages table
            'id' // Local key on appointments table
        );
    }
}
