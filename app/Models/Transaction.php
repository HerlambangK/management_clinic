<?php

namespace App\Models;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use BelongsToStore, HasFactory, SoftDeletes;

    public const STATUSES = [
        'pending' => 'Belum Bayar',
        'partial' => 'Bayar Sebagian',
        'paid' => 'Lunas',
        'cancelled' => 'Dibatalkan',
        'refunded' => 'Dikembalikan',
    ];

    public const PAYMENT_METHODS = [
        'cash' => 'Tunai',
        'debit_card' => 'Kartu Debit',
        'credit_card' => 'Kartu Kredit',
        'transfer' => 'Transfer Bank',
        'qris' => 'QRIS',
        'other' => 'Lainnya',
    ];

    protected $fillable = [
        'store_id',
        'invoice_number',
        'customer_id',
        'appointment_id',
        'cashier_id',
        'subtotal',
        'discount_amount',
        'discount_type',
        'points_used',
        'points_discount',
        'tax_amount',
        'total_amount',
        'paid_amount',
        'change_amount',
        'status',
        'notes',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'change_amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->invoice_number)) {
                // Use created_at if set, otherwise use now()
                $date = $transaction->created_at ?? now();
                $transaction->invoice_number = self::generateInvoiceNumber($date);
            }
        });
    }

    public static function generateInvoiceNumber($date = null): string
    {
        $prefix = 'INV';
        $date = $date ? \Carbon\Carbon::parse($date) : now();
        $dateStr = $date->format('Ymd');

        $lastTransaction = self::where('invoice_number', 'like', $prefix.$dateStr.'%')
            ->orderBy('invoice_number', 'desc')
            ->first();

        $sequence = $lastTransaction ? ((int) substr($lastTransaction->invoice_number, -4)) + 1 : 1;

        return $prefix.$dateStr.str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp '.number_format($this->subtotal, 0, ',', '.');
    }

    public function getFormattedTotalAmountAttribute(): string
    {
        return 'Rp '.number_format($this->total_amount, 0, ',', '.');
    }

    public function getFormattedPaidAmountAttribute(): string
    {
        return 'Rp '.number_format($this->paid_amount, 0, ',', '.');
    }

    public function getFormattedDiscountAmountAttribute(): string
    {
        return 'Rp '.number_format($this->discount_amount, 0, ',', '.');
    }

    public function getOutstandingAmountAttribute(): float
    {
        return max(0, $this->total_amount - $this->paid_amount);
    }

    public function getFormattedOutstandingAmountAttribute(): string
    {
        return 'Rp '.number_format($this->outstanding_amount, 0, ',', '.');
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->status === 'paid';
    }

    public function calculateTotals(): void
    {
        $this->subtotal = $this->items->sum('total_price');
        $this->total_amount = $this->subtotal - $this->discount_amount + $this->tax_amount;
    }

    public function addPayment(float $amount, string $method = 'cash', ?string $reference = null, ?string $notes = null): Payment
    {
        $payment = $this->payments()->create([
            'amount' => $amount,
            'payment_method' => $method,
            'reference_number' => $reference,
            'notes' => $notes,
            'received_by' => auth()->id(),
            'paid_at' => now(),
        ]);

        $this->paid_amount = $this->payments()->sum('amount');

        if ($this->paid_amount >= $this->total_amount) {
            $this->status = 'paid';
            $this->paid_at = now();
            $this->change_amount = $this->paid_amount - $this->total_amount;
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partial';
        }

        $this->save();

        return $payment;
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForCustomer($query, int $customerId)
    {
        return $query->where('customer_id', $customerId);
    }
}
