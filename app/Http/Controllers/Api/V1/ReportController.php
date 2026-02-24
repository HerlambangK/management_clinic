<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\CustomerPackage;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\TreatmentRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Get complete report data
     */
    public function index(Request $request): JsonResponse
    {
        $period = $request->get('period', 'bulan_ini');
        [$start, $end] = $this->getPeriodDates($period, $request);
        [$prevStart, $prevEnd] = $this->getPreviousPeriodDates($start, $end);

        return response()->json([
            'data' => [
                'summary' => $this->getSummary($start, $end, $prevStart, $prevEnd),
                'sales_report' => $this->getSalesData($start, $end),
                'service_report' => $this->getServicesData($start, $end),
                'customer_stats' => $this->getCustomerStats($start, $end, $prevStart, $prevEnd),
                'top_customers' => $this->getTopCustomers($start, $end),
                'staff_report' => $this->getStaffData($start, $end),
                'package_stats' => $this->getPackageStats($start, $end),
                'packages' => $this->getPackagesData($start, $end),
            ],
        ]);
    }

    /**
     * Get report summary only
     */
    public function summary(Request $request): JsonResponse
    {
        $period = $request->get('period', 'bulan_ini');
        [$start, $end] = $this->getPeriodDates($period, $request);
        [$prevStart, $prevEnd] = $this->getPreviousPeriodDates($start, $end);

        return response()->json([
            'data' => $this->getSummary($start, $end, $prevStart, $prevEnd),
        ]);
    }

    /**
     * Get revenue/sales report
     */
    public function revenue(Request $request): JsonResponse
    {
        $period = $request->get('period', 'bulan_ini');
        [$start, $end] = $this->getPeriodDates($period, $request);

        return response()->json([
            'data' => $this->getSalesData($start, $end),
        ]);
    }

    /**
     * Get services report
     */
    public function services(Request $request): JsonResponse
    {
        $period = $request->get('period', 'bulan_ini');
        [$start, $end] = $this->getPeriodDates($period, $request);

        return response()->json([
            'data' => $this->getServicesData($start, $end),
        ]);
    }

    /**
     * Get customers report
     */
    public function customers(Request $request): JsonResponse
    {
        $period = $request->get('period', 'bulan_ini');
        [$start, $end] = $this->getPeriodDates($period, $request);
        [$prevStart, $prevEnd] = $this->getPreviousPeriodDates($start, $end);

        return response()->json([
            'data' => [
                'stats' => $this->getCustomerStats($start, $end, $prevStart, $prevEnd),
                'top_customers' => $this->getTopCustomers($start, $end),
            ],
        ]);
    }

    /**
     * Get staff report
     */
    public function staff(Request $request): JsonResponse
    {
        $period = $request->get('period', 'bulan_ini');
        [$start, $end] = $this->getPeriodDates($period, $request);

        return response()->json([
            'data' => $this->getStaffData($start, $end),
        ]);
    }

    /**
     * Get packages report
     */
    public function packages(Request $request): JsonResponse
    {
        $period = $request->get('period', 'bulan_ini');
        [$start, $end] = $this->getPeriodDates($period, $request);

        return response()->json([
            'data' => [
                'stats' => $this->getPackageStats($start, $end),
                'packages' => $this->getPackagesData($start, $end),
            ],
        ]);
    }

    // ========================================
    // Private Helper Methods
    // ========================================

    private function getPeriodDates(string $period, Request $request): array
    {
        switch ($period) {
            case 'hari_ini':
                return [now()->startOfDay(), now()->endOfDay()];
            case 'minggu_ini':
                return [now()->startOfWeek(), now()->endOfWeek()];
            case 'bulan_ini':
                return [now()->startOfMonth(), now()->endOfMonth()];
            case 'tahun_ini':
                return [now()->startOfYear(), now()->endOfYear()];
            case 'custom':
                $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
                $endDate = $request->get('end_date', now()->format('Y-m-d'));

                return [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay(),
                ];
            default:
                return [now()->startOfMonth(), now()->endOfMonth()];
        }
    }

    private function getPreviousPeriodDates(Carbon $start, Carbon $end): array
    {
        $diff = $start->diffInDays($end);

        return [
            $start->copy()->subDays($diff + 1),
            $start->copy()->subDay(),
        ];
    }

    private function getSummary(Carbon $start, Carbon $end, Carbon $prevStart, Carbon $prevEnd): array
    {
        // Current period stats
        $currentRevenue = Transaction::whereBetween('created_at', [$start, $end])->paid()->sum('total_amount');
        $currentTransactions = Transaction::whereBetween('created_at', [$start, $end])->paid()->count();
        $currentCustomers = Customer::whereBetween('created_at', [$start, $end])->count();
        $currentAverage = $currentTransactions > 0 ? $currentRevenue / $currentTransactions : 0;

        // Previous period stats
        $prevRevenue = Transaction::whereBetween('created_at', [$prevStart, $prevEnd])->paid()->sum('total_amount');
        $prevTransactions = Transaction::whereBetween('created_at', [$prevStart, $prevEnd])->paid()->count();
        $prevCustomers = Customer::whereBetween('created_at', [$prevStart, $prevEnd])->count();
        $prevAverage = $prevTransactions > 0 ? $prevRevenue / $prevTransactions : 0;

        // Calculate changes
        $revenueChange = $prevRevenue > 0 ? (($currentRevenue - $prevRevenue) / $prevRevenue) * 100 : 0;
        $transactionChange = $prevTransactions > 0 ? (($currentTransactions - $prevTransactions) / $prevTransactions) * 100 : 0;
        $customerChange = $prevCustomers > 0 ? (($currentCustomers - $prevCustomers) / $prevCustomers) * 100 : 0;
        $averageChange = $prevAverage > 0 ? (($currentAverage - $prevAverage) / $prevAverage) * 100 : 0;

        return [
            'total_revenue' => (int) $currentRevenue,
            'total_transactions' => $currentTransactions,
            'new_customers' => $currentCustomers,
            'average_transaction' => (int) $currentAverage,
            'revenue_change' => round($revenueChange, 1),
            'transaction_change' => round($transactionChange, 1),
            'customer_change' => round($customerChange, 1),
            'average_change' => round($averageChange, 1),
        ];
    }

    private function getSalesData(Carbon $start, Carbon $end): array
    {
        $data = Transaction::query()
            ->whereBetween('created_at', [$start, $end])
            ->paid()
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as transactions')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $data->map(fn ($item) => [
            'date' => $item->date,
            'revenue' => (int) $item->revenue,
            'transactions' => $item->transactions,
        ])->toArray();
    }

    private function getServicesData(Carbon $start, Carbon $end): array
    {
        $data = TransactionItem::query()
            ->where('item_type', 'service')
            ->whereHas('transaction', function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end])->paid();
            })
            ->select('service_id', 'item_name', DB::raw('SUM(total_price) as revenue'), DB::raw('SUM(quantity) as count'))
            ->groupBy('service_id', 'item_name')
            ->orderByDesc('revenue')
            ->limit(20)
            ->get();

        return $data->map(fn ($item) => [
            'id' => $item->service_id,
            'name' => $item->item_name,
            'count' => (int) $item->count,
            'revenue' => (int) $item->revenue,
        ])->toArray();
    }

    private function getCustomerStats(Carbon $start, Carbon $end, Carbon $prevStart, Carbon $prevEnd): array
    {
        $totalCustomers = Customer::count();
        $newCustomers = Customer::whereBetween('created_at', [$start, $end])->count();
        $prevNewCustomers = Customer::whereBetween('created_at', [$prevStart, $prevEnd])->count();
        $growthRate = $prevNewCustomers > 0 ? (($newCustomers - $prevNewCustomers) / $prevNewCustomers) * 100 : 0;

        // Active customers (made transaction in period)
        $activeCustomers = Transaction::whereBetween('created_at', [$start, $end])
            ->paid()
            ->distinct('customer_id')
            ->count('customer_id');

        return [
            'total_customers' => $totalCustomers,
            'new_customers' => $newCustomers,
            'active_customers' => $activeCustomers,
            'growth_rate' => round($growthRate, 1),
        ];
    }

    private function getTopCustomers(Carbon $start, Carbon $end): array
    {
        $data = Customer::query()
            ->whereHas('transactions', function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end])->paid();
            })
            ->withCount(['transactions' => function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end])->paid();
            }])
            ->withSum(['transactions' => function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end])->paid();
            }], 'total_amount')
            ->orderByDesc('transactions_sum_total_amount')
            ->limit(10)
            ->get();

        return $data->map(fn ($customer) => [
            'id' => $customer->id,
            'name' => $customer->name,
            'phone' => $customer->phone,
            'total_transactions' => $customer->transactions_count,
            'total_spent' => (int) ($customer->transactions_sum_total_amount ?? 0),
        ])->toArray();
    }

    private function getStaffData(Carbon $start, Carbon $end): array
    {
        $staffMembers = User::whereIn('role', ['therapist', 'staff', 'admin'])
            ->where('is_active', true)
            ->get();

        $result = [];

        foreach ($staffMembers as $staff) {
            // Appointments handled
            $appointments = Appointment::where('staff_id', $staff->id)
                ->whereBetween('appointment_date', [$start, $end])
                ->get();

            $appointmentsCount = $appointments->count();
            $completedCount = $appointments->where('status', 'completed')->count();

            // Treatments done
            $treatments = TreatmentRecord::where('staff_id', $staff->id)
                ->whereBetween('created_at', [$start, $end])
                ->count();

            // Revenue from transactions where staff did the appointment
            $appointmentIds = $appointments->pluck('id');
            $revenue = Transaction::whereIn('appointment_id', $appointmentIds)
                ->paid()
                ->sum('total_amount');

            $result[] = [
                'id' => $staff->id,
                'name' => $staff->name,
                'role' => $staff->role,
                'appointments' => $appointmentsCount,
                'completed' => $completedCount,
                'treatments' => $treatments,
                'revenue' => (int) $revenue,
            ];
        }

        // Sort by revenue
        usort($result, fn ($a, $b) => $b['revenue'] <=> $a['revenue']);

        return $result;
    }

    private function getPackageStats(Carbon $start, Carbon $end): array
    {
        $totalSold = CustomerPackage::whereBetween('purchased_at', [$start, $end])->count();
        $totalRevenue = CustomerPackage::whereBetween('purchased_at', [$start, $end])->sum('price_paid');
        $activePackages = CustomerPackage::where('status', 'active')->count();

        return [
            'total_sold' => $totalSold,
            'total_revenue' => (int) $totalRevenue,
            'active_packages' => $activePackages,
        ];
    }

    private function getPackagesData(Carbon $start, Carbon $end): array
    {
        $data = CustomerPackage::query()
            ->whereBetween('purchased_at', [$start, $end])
            ->with('package:id,name')
            ->select('package_id', DB::raw('COUNT(*) as sold'), DB::raw('SUM(price_paid) as revenue'))
            ->groupBy('package_id')
            ->orderByDesc('revenue')
            ->get();

        return $data->map(fn ($item) => [
            'id' => $item->package_id,
            'name' => $item->package->name ?? 'Unknown',
            'sold' => $item->sold,
            'revenue' => (int) $item->revenue,
        ])->toArray();
    }
}
