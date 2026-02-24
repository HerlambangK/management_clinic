<?php

namespace App\Exports;

use App\Models\Transaction;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RevenueExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    protected Carbon $startDate;

    protected Carbon $endDate;

    protected int $rowNumber = 0;

    public function __construct(Carbon $startDate, Carbon $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Transaction::with(['customer', 'items', 'payments'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->paid()
            ->orderBy('created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Invoice',
            'Tanggal',
            'Customer',
            'Item',
            'Subtotal',
            'Diskon',
            'Total',
            'Metode Bayar',
            'Status',
        ];
    }

    /**
     * @param  Transaction  $trx
     */
    public function map($trx): array
    {
        $this->rowNumber++;
        $items = $trx->items->pluck('item_name')->implode(', ');
        $methods = $trx->payments->pluck('payment_method_label')->unique()->implode(', ');

        return [
            $this->rowNumber,
            $trx->invoice_number,
            format_datetime($trx->created_at),
            $trx->customer?->name ?? '-',
            $items,
            (float) $trx->subtotal,
            (float) $trx->discount_amount,
            (float) $trx->total_amount,
            $methods,
            $trx->status_label,
        ];
    }
}
