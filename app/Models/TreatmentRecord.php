<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class TreatmentRecord extends Model
{
    /** @use HasFactory<\Database\Factories\TreatmentRecordFactory> */
    use BelongsToStore, HasFactory, SoftDeletes;

    protected $fillable = [
        'store_id',
        'appointment_id',
        'customer_id',
        'staff_id',
        'notes',
        'before_photos',
        'after_photos',
        'recommendations',
        'follow_up_date',
    ];

    protected function casts(): array
    {
        return [
            'before_photos' => 'array',
            'after_photos' => 'array',
            'follow_up_date' => 'date',
        ];
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * Get URLs for before photos
     */
    public function getBeforePhotoUrlsAttribute(): array
    {
        if (empty($this->before_photos)) {
            return [];
        }

        return array_map(fn ($photo) => Storage::url($photo), $this->before_photos);
    }

    /**
     * Get URLs for after photos
     */
    public function getAfterPhotoUrlsAttribute(): array
    {
        if (empty($this->after_photos)) {
            return [];
        }

        return array_map(fn ($photo) => Storage::url($photo), $this->after_photos);
    }

    /**
     * Get first before photo URL (for backward compatibility)
     */
    public function getBeforePhotoUrlAttribute(): ?string
    {
        $urls = $this->before_photo_urls;

        return ! empty($urls) ? $urls[0] : null;
    }

    /**
     * Get first after photo URL (for backward compatibility)
     */
    public function getAfterPhotoUrlAttribute(): ?string
    {
        $urls = $this->after_photo_urls;

        return ! empty($urls) ? $urls[0] : null;
    }

    /**
     * Check if has any photos
     */
    public function getHasPhotosAttribute(): bool
    {
        return ! empty($this->before_photos) || ! empty($this->after_photos);
    }

    public function scopeForCustomer($query, int $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeWithPhotos($query)
    {
        return $query->where(function ($q) {
            $q->whereNotNull('before_photos')
                ->orWhereNotNull('after_photos');
        });
    }

    /**
     * Get items from the related transaction
     */
    public function getTransactionItemsAttribute(): Collection
    {
        $transaction = $this->appointment?->transaction;

        if (! $transaction) {
            return new Collection;
        }

        return $transaction->items;
    }

    /**
     * Check if has transaction with items
     */
    public function getHasTransactionItemsAttribute(): bool
    {
        return $this->transaction_items->isNotEmpty();
    }
}
