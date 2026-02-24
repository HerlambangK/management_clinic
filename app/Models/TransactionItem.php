<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionItem extends Model
{
    use BelongsToStore;

    public const ITEM_TYPES = [
        'service' => 'Layanan',
        'package' => 'Paket',
        'product' => 'Produk',
        'other' => 'Lainnya',
    ];

    protected $fillable = [
        'store_id',
        'transaction_id',
        'item_type',
        'service_id',
        'package_id',
        'product_id',
        'customer_package_id',
        'item_name',
        'quantity',
        'unit_price',
        'discount',
        'total_price',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'discount' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function customerPackage(): BelongsTo
    {
        return $this->belongsTo(CustomerPackage::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getItemTypeLabelAttribute(): string
    {
        return self::ITEM_TYPES[$this->item_type] ?? $this->item_type;
    }

    public function getFormattedUnitPriceAttribute(): string
    {
        return 'Rp '.number_format($this->unit_price, 0, ',', '.');
    }

    public function getFormattedTotalPriceAttribute(): string
    {
        return 'Rp '.number_format($this->total_price, 0, ',', '.');
    }

    public function getFormattedDiscountAttribute(): string
    {
        return 'Rp '.number_format($this->discount, 0, ',', '.');
    }

    public function calculateTotal(): void
    {
        $this->total_price = ($this->unit_price * $this->quantity) - $this->discount;
    }
}
