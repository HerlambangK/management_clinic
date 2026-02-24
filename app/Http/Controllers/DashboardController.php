<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = $this->getStats();
        $revenueChart = $this->getRevenueChart(7);
        $popularServices = $this->getPopularServices(5);
        $todayAppointments = $this->getTodayAppointments();
        $recentTransactions = $this->getRecentTransactions(5);

        return view('dashboard.index', compact(
            'stats',
            'revenueChart',
            'popularServices',
            'todayAppointments',
            'recentTransactions'
        ));
    }

    /**
     * @return array<string, mixed>
     */
    private function getStats(): array
    {
        $today = now()->toDateString();
        $weekAgo = now()->subDays(7)->toDateString();
        $monthStart = now()->startOfMonth()->toDateString();

        return [
            'revenue_today' => Transaction::today()->paid()->sum('total_amount'),
            'revenue_month' => Transaction::whereDate('created_at', '>=', $monthStart)->paid()->sum('total_amount'),
            'appointments_today' => Appointment::whereDate('appointment_date', $today)->count(),
            'pending_appointments' => Appointment::whereDate('appointment_date', $today)
                ->where('status', 'pending')->count(),
            'new_customers_week' => Customer::where('created_at', '>=', $weekAgo)->count(),
            'total_customers' => Customer::count(),
            'completed_today' => Appointment::whereDate('appointment_date', $today)
                ->where('status', 'completed')->count(),
            'transactions_today' => Transaction::today()->count(),
            'transactions_pending' => Transaction::today()->where('status', 'pending')->count(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getRevenueChart(int $days): array
    {
        $labels = [];
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d M');
            $data[] = (float) Transaction::whereDate('created_at', $date->toDateString())
                ->paid()
                ->sum('total_amount');
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Transaction>
     */
    private function getRecentTransactions(int $limit)
    {
        return Transaction::with(['customer'])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection<int, Service>
     */
    private function getPopularServices(int $limit)
    {
        return Service::query()
            ->withCount('appointments')
            ->orderByDesc('appointments_count')
            ->take($limit)
            ->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Appointment>
     */
    private function getTodayAppointments()
    {
        return Appointment::with(['customer', 'service', 'staff'])
            ->whereDate('appointment_date', today())
            ->orderBy('start_time')
            ->get();
    }
}
