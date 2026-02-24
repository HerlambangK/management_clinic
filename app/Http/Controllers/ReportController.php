<?php

namespace App\Http\Controllers;

use App\Exports\CustomersExport;
use App\Exports\RevenueExport;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\CustomerPackage;
use App\Models\LoyaltyPoint;
use App\Models\LoyaltyRedemption;
use App\Models\Product;
use App\Models\ReferralLog;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\TreatmentRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function index(): View
    {
        $todayStats = $this->getTodayStats();
        $monthStats = $this->getMonthStats();

        return view('reports.index', compact('todayStats', 'monthStats'));
    }

    public function revenue(Request $request): View
    {
        $period = $request->get('period', 'daily');
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        if ($period === 'daily') {
            $data = $this->getDailyRevenue($start, $end);
        } else {
            $data = $this->getMonthlyRevenue($start, $end);
        }

        $summary = [
            'total_revenue' => Transaction::whereBetween('created_at', [$start, $end])->paid()->sum('total_amount'),
            'total_transactions' => Transaction::whereBetween('created_at', [$start, $end])->paid()->count(),
            'average_transaction' => Transaction::whereBetween('created_at', [$start, $end])->paid()->avg('total_amount') ?? 0,
            'total_discount' => Transaction::whereBetween('created_at', [$start, $end])->paid()->sum('discount_amount'),
        ];

        $paymentMethods = Transaction::whereBetween('transactions.created_at', [$start, $end])
            ->paid()
            ->join('payments', 'transactions.id', '=', 'payments.transaction_id')
            ->select('payments.payment_method', DB::raw('SUM(payments.amount) as total'))
            ->groupBy('payments.payment_method')
            ->get();

        return view('reports.revenue', compact('data', 'summary', 'paymentMethods', 'period', 'startDate', 'endDate'));
    }

    public function customers(Request $request): View
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $newCustomers = Customer::whereBetween('created_at', [$start, $end])->count();
        $totalCustomers = Customer::count();

        $topCustomers = Customer::query()
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
            ->take(20)
            ->get();

        $customerGrowth = $this->getCustomerGrowth($start, $end);

        $genderStats = Customer::select('gender', DB::raw('count(*) as total'))
            ->whereNotNull('gender')
            ->groupBy('gender')
            ->get();

        return view('reports.customers', compact(
            'newCustomers',
            'totalCustomers',
            'topCustomers',
            'customerGrowth',
            'genderStats',
            'startDate',
            'endDate'
        ));
    }

    public function services(Request $request): View
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $popularServices = Service::query()
            ->withCount(['appointments' => function ($query) use ($start, $end) {
                $query->whereBetween('appointment_date', [$start, $end]);
            }])
            ->orderByDesc('appointments_count')
            ->get();

        $serviceRevenue = TransactionItem::query()
            ->where('item_type', 'service')
            ->whereHas('transaction', function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end])->paid();
            })
            ->select('service_id', 'item_name', DB::raw('SUM(total_price) as revenue'), DB::raw('SUM(quantity) as qty'))
            ->groupBy('service_id', 'item_name')
            ->orderByDesc('revenue')
            ->get();

        $packageSales = CustomerPackage::query()
            ->whereBetween('purchased_at', [$start, $end])
            ->with('package')
            ->select('package_id', DB::raw('COUNT(*) as sold'), DB::raw('SUM(price_paid) as revenue'))
            ->groupBy('package_id')
            ->orderByDesc('revenue')
            ->get();

        $totalServiceRevenue = $serviceRevenue->sum('revenue');
        $totalPackageRevenue = $packageSales->sum('revenue');

        return view('reports.services', compact(
            'popularServices',
            'serviceRevenue',
            'packageSales',
            'totalServiceRevenue',
            'totalPackageRevenue',
            'startDate',
            'endDate'
        ));
    }

    public function exportRevenue(Request $request): BinaryFileResponse
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $filename = 'laporan-pendapatan-'.$startDate.'-'.$endDate.'.xlsx';

        return Excel::download(new RevenueExport($start, $end), $filename);
    }

    public function exportCustomers(Request $request): BinaryFileResponse
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $filename = 'laporan-customer-'.$startDate.'-'.$endDate.'.xlsx';

        return Excel::download(new CustomersExport($start, $end), $filename);
    }

    /**
     * @return array<string, mixed>
     */
    private function getTodayStats(): array
    {
        return [
            'revenue' => Transaction::today()->paid()->sum('total_amount'),
            'transactions' => Transaction::today()->count(),
            'transactions_paid' => Transaction::today()->paid()->count(),
            'appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'appointments_completed' => Appointment::whereDate('appointment_date', today())->where('status', 'completed')->count(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getMonthStats(): array
    {
        $monthStart = now()->startOfMonth();

        return [
            'revenue' => Transaction::where('created_at', '>=', $monthStart)->paid()->sum('total_amount'),
            'transactions' => Transaction::where('created_at', '>=', $monthStart)->count(),
            'new_customers' => Customer::where('created_at', '>=', $monthStart)->count(),
            'packages_sold' => CustomerPackage::where('purchased_at', '>=', $monthStart)->count(),
        ];
    }

    /**
     * @return \Illuminate\Support\Collection<int, object>
     */
    private function getDailyRevenue(Carbon $start, Carbon $end)
    {
        return Transaction::query()
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
    }

    /**
     * @return \Illuminate\Support\Collection<int, object>
     */
    private function getMonthlyRevenue(Carbon $start, Carbon $end)
    {
        return Transaction::query()
            ->whereBetween('created_at', [$start, $end])
            ->paid()
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as transactions')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection<int, object>
     */
    private function getCustomerGrowth(Carbon $start, Carbon $end)
    {
        return Customer::query()
            ->whereBetween('created_at', [$start, $end])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function appointments(Request $request): View
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Summary stats
        $summary = [
            'total' => Appointment::whereBetween('appointment_date', [$start, $end])->count(),
            'completed' => Appointment::whereBetween('appointment_date', [$start, $end])->where('status', 'completed')->count(),
            'cancelled' => Appointment::whereBetween('appointment_date', [$start, $end])->where('status', 'cancelled')->count(),
            'no_show' => Appointment::whereBetween('appointment_date', [$start, $end])->where('status', 'no_show')->count(),
        ];

        // Status breakdown
        $statusBreakdown = Appointment::whereBetween('appointment_date', [$start, $end])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(fn ($item) => [$item->status => $item->count]);

        // Source breakdown
        $sourceBreakdown = Appointment::whereBetween('appointment_date', [$start, $end])
            ->select('source', DB::raw('COUNT(*) as count'))
            ->groupBy('source')
            ->get()
            ->mapWithKeys(fn ($item) => [$item->source => $item->count]);

        // Daily appointments
        $dailyData = Appointment::whereBetween('appointment_date', [$start, $end])
            ->select(
                DB::raw('DATE(appointment_date) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Peak hours analysis
        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            $hourExpression = "CAST(strftime('%H', start_time) AS INTEGER)";
        } else {
            $hourExpression = 'HOUR(start_time)';
        }

        $peakHours = Appointment::whereBetween('appointment_date', [$start, $end])
            ->select(
                DB::raw("{$hourExpression} as hour"),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Pre-format peak hour labels for the chart
        $peakHourLabels = $peakHours->pluck('hour')->map(function ($h) {
            return str_pad((string) $h, 2, '0', STR_PAD_LEFT).':00';
        })->values();

        // Completion rate
        $completionRate = $summary['total'] > 0
            ? round(($summary['completed'] / $summary['total']) * 100, 1)
            : 0;

        return view('reports.appointments', compact(
            'summary',
            'statusBreakdown',
            'sourceBreakdown',
            'dailyData',
            'peakHours',
            'peakHourLabels',
            'completionRate',
            'startDate',
            'endDate'
        ));
    }

    public function staff(Request $request): View
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Staff with roles therapist, staff, admin (excluding owner)
        $staffMembers = User::whereIn('role', ['therapist', 'staff', 'admin'])
            ->where('is_active', true)
            ->get();

        $staffPerformance = [];

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

            // Transactions as cashier
            $cashierTransactions = Transaction::where('cashier_id', $staff->id)
                ->whereBetween('created_at', [$start, $end])
                ->paid()
                ->count();

            $staffPerformance[] = [
                'staff' => $staff,
                'appointments' => $appointmentsCount,
                'completed' => $completedCount,
                'treatments' => $treatments,
                'revenue' => $revenue,
                'cashier_transactions' => $cashierTransactions,
                'completion_rate' => $appointmentsCount > 0
                    ? round(($completedCount / $appointmentsCount) * 100, 1)
                    : 0,
            ];
        }

        // Sort by revenue
        usort($staffPerformance, fn ($a, $b) => $b['revenue'] <=> $a['revenue']);

        // Summary
        $summary = [
            'total_staff' => count($staffMembers),
            'total_appointments' => array_sum(array_column($staffPerformance, 'appointments')),
            'total_treatments' => array_sum(array_column($staffPerformance, 'treatments')),
            'total_revenue' => array_sum(array_column($staffPerformance, 'revenue')),
        ];

        return view('reports.staff', compact(
            'staffPerformance',
            'summary',
            'startDate',
            'endDate'
        ));
    }

    public function loyalty(Request $request): View
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Points summary
        $pointsSummary = [
            'earned' => LoyaltyPoint::whereBetween('created_at', [$start, $end])
                ->where('type', 'earn')
                ->sum('points'),
            'redeemed' => abs(LoyaltyPoint::whereBetween('created_at', [$start, $end])
                ->where('type', 'redeem')
                ->sum('points')),
            'bonus' => LoyaltyPoint::whereBetween('created_at', [$start, $end])
                ->where('type', 'bonus')
                ->sum('points'),
            'adjusted' => LoyaltyPoint::whereBetween('created_at', [$start, $end])
                ->where('type', 'adjust')
                ->sum('points'),
        ];

        // Daily points
        $dailyPoints = LoyaltyPoint::whereBetween('created_at', [$start, $end])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(CASE WHEN type = "earn" THEN points ELSE 0 END) as earned'),
                DB::raw('SUM(CASE WHEN type = "redeem" THEN ABS(points) ELSE 0 END) as redeemed')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top earners
        $topEarners = Customer::query()
            ->whereHas('loyaltyPoints', function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end])->where('type', 'earn');
            })
            ->withSum(['loyaltyPoints as points_earned' => function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end])->where('type', 'earn');
            }], 'points')
            ->orderByDesc('points_earned')
            ->take(10)
            ->get();

        // Top redeemers
        $topRedeemers = Customer::query()
            ->whereHas('loyaltyPoints', function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end])->where('type', 'redeem');
            })
            ->withSum(['loyaltyPoints as points_redeemed' => function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end])->where('type', 'redeem');
            }], 'points')
            ->orderBy('points_redeemed')
            ->take(10)
            ->get();

        // Redemption stats
        $redemptionStats = LoyaltyRedemption::whereBetween('created_at', [$start, $end])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(fn ($item) => [$item->status => $item->count]);

        // Tier distribution
        $tierDistribution = Customer::select('loyalty_tier', DB::raw('COUNT(*) as count'))
            ->groupBy('loyalty_tier')
            ->get()
            ->mapWithKeys(fn ($item) => [$item->loyalty_tier ?? 'bronze' => $item->count]);

        // Referral stats
        $referralStats = [
            'total' => ReferralLog::whereBetween('created_at', [$start, $end])->count(),
            'rewarded' => ReferralLog::whereBetween('created_at', [$start, $end])->where('status', 'rewarded')->count(),
            'points_given' => ReferralLog::whereBetween('created_at', [$start, $end])
                ->where('status', 'rewarded')
                ->sum(DB::raw('referrer_points + referee_points')),
        ];

        return view('reports.loyalty', compact(
            'pointsSummary',
            'dailyPoints',
            'topEarners',
            'topRedeemers',
            'redemptionStats',
            'tierDistribution',
            'referralStats',
            'startDate',
            'endDate'
        ));
    }

    public function products(Request $request): View
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Product sales
        $productSales = TransactionItem::query()
            ->where('item_type', 'product')
            ->whereHas('transaction', function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end])->paid();
            })
            ->select(
                'product_id',
                'item_name',
                DB::raw('SUM(quantity) as qty_sold'),
                DB::raw('SUM(total_price) as revenue')
            )
            ->groupBy('product_id', 'item_name')
            ->orderByDesc('revenue')
            ->get();

        // Summary
        $summary = [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'total_sold' => $productSales->sum('qty_sold'),
            'total_revenue' => $productSales->sum('revenue'),
        ];

        // Low stock products (stock <= min_stock but > 0)
        $lowStockProducts = Product::where('is_active', true)
            ->where('track_stock', true)
            ->where('stock', '>', 0)
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock')
            ->get();

        // Out of stock
        $outOfStock = Product::where('is_active', true)
            ->where('track_stock', true)
            ->where('stock', 0)
            ->get();

        // Stock value
        $stockValue = Product::where('is_active', true)
            ->where('track_stock', true)
            ->sum(DB::raw('stock * cost_price'));

        // Daily sales
        $dailySales = TransactionItem::query()
            ->where('item_type', 'product')
            ->whereHas('transaction', function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end])->paid();
            })
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->select(
                DB::raw('DATE(transactions.created_at) as date'),
                DB::raw('SUM(transaction_items.quantity) as qty'),
                DB::raw('SUM(transaction_items.total_price) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('reports.products', compact(
            'productSales',
            'summary',
            'lowStockProducts',
            'outOfStock',
            'stockValue',
            'dailySales',
            'startDate',
            'endDate'
        ));
    }
}
