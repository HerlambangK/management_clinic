<?php

namespace App\Exports;

use App\Models\Customer;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
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
        return Customer::query()
            ->withCount(['transactions' => function ($query) {
                $query->whereBetween('created_at', [$this->startDate, $this->endDate])->paid();
            }])
            ->withSum(['transactions' => function ($query) {
                $query->whereBetween('created_at', [$this->startDate, $this->endDate])->paid();
            }], 'total_amount')
            ->orderByDesc('transactions_sum_total_amount')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Telepon',
            'Email',
            'Gender',
            'Total Transaksi',
            'Total Belanja',
            'Terdaftar Sejak',
        ];
    }

    /**
     * @param  Customer  $customer
     */
    public function map($customer): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $customer->name,
            $customer->phone,
            $customer->email ?? '-',
            $customer->gender ?? '-',
            (int) $customer->transactions_count,
            (float) ($customer->transactions_sum_total_amount ?? 0),
            format_date($customer->created_at),
        ];
    }
}
