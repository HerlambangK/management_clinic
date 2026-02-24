<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function index(Request $request): JsonResponse
    {
        $today = now()->startOfDay();
        $startOfMonth = now()->startOfMonth();

        return response()->json([
            'data' => [
                'today' => $this->getTodayStats($today),
                'month' => $this->getMonthStats($startOfMonth),
                'today_appointments' => $this->getTodayAppointments($today),
                'revenue_chart' => $this->getRevenueChart(),
            ],
        ]);
    }

    /**
     * Get today's statistics
     */
    private function getTodayStats($today): array
    {
        return [
            'revenue' => (int) Transaction::whereDate('paid_at', $today)
                ->where('status', 'paid')
                ->sum('total_amount'),
            'appointments' => Appointment::whereDate('appointment_date', $today)->count(),
            'completed' => Appointment::whereDate('appointment_date', $today)
                ->where('status', 'completed')
                ->count(),
            'new_customers' => Customer::whereDate('created_at', $today)->count(),
        ];
    }

    /**
     * Get month statistics
     */
    private function getMonthStats($startOfMonth): array
    {
        return [
            'total_revenue' => (int) Transaction::where('paid_at', '>=', $startOfMonth)
                ->where('status', 'paid')
                ->sum('total_amount'),
            'total_appointments' => Appointment::where('appointment_date', '>=', $startOfMonth)->count(),
            'completed' => Appointment::where('appointment_date', '>=', $startOfMonth)
                ->where('status', 'completed')
                ->count(),
            'new_customers' => Customer::where('created_at', '>=', $startOfMonth)->count(),
            'total_customers' => Customer::count(),
        ];
    }

    /**
     * Get today's appointments
     */
    private function getTodayAppointments($today): array
    {
        $appointments = Appointment::with(['customer', 'service', 'staff'])
            ->whereDate('appointment_date', $today)
            ->orderBy('start_time')
            ->limit(10)
            ->get();

        return AppointmentResource::collection($appointments)->resolve();
    }

    /**
     * Get revenue chart data (last 7 days)
     */
    private function getRevenueChart(): array
    {
        $days = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $days->push([
                'date' => $date->format('Y-m-d'),
                'day_name' => $date->format('D'),
                'amount' => (int) Transaction::whereDate('paid_at', $date)
                    ->where('status', 'paid')
                    ->sum('total_amount'),
            ]);
        }

        return $days->toArray();
    }

    /**
     * Get summary statistics
     */
    public function summary(): JsonResponse
    {
        return response()->json([
            'data' => [
                'total_customers' => Customer::count(),
                'total_appointments' => Appointment::count(),
                'total_revenue' => Transaction::where('status', 'paid')->sum('total_amount'),
                'popular_services' => $this->getPopularServices(),
            ],
        ]);
    }

    /**
     * Get popular services
     */
    private function getPopularServices(): array
    {
        return Appointment::select('service_id', DB::raw('count(*) as total'))
            ->with('service:id,name')
            ->where('status', 'completed')
            ->groupBy('service_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn ($item) => [
                'service_id' => $item->service_id,
                'service_name' => $item->service?->name,
                'total' => $item->total,
            ])
            ->toArray();
    }
}
