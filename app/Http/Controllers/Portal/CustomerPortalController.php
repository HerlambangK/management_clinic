<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyReward;
use App\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerPortalController extends Controller
{
    protected function customer()
    {
        return Auth::guard('customer')->user();
    }

    public function dashboard(AttendanceService $attendanceService): View
    {
        $customer = $this->customer();
        $attendanceHistory = collect();
        $activeAttendance = null;
        $attendanceQrPayload = null;
        $attendanceIsOpen = false;
        $hasCompletedToday = false;

        $upcomingAppointments = $customer->appointments()
            ->with(['service', 'staff'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('appointment_date', '>=', now()->toDateString())
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->take(3)
            ->get();

        $recentTreatments = $customer->treatmentRecords()
            ->with('appointment.service')
            ->latest('created_at')
            ->take(3)
            ->get();

        $activePackages = $customer->activePackages()
            ->with('package')
            ->take(3)
            ->get();

        if (has_feature('attendance')) {
            $attendanceHistory = $customer->attendances()
                ->latest('checked_in_at')
                ->take(5)
                ->get();

            $activeAttendance = $customer->attendances()
                ->whereNull('checked_out_at')
                ->latest('checked_in_at')
                ->first();

            $hasCompletedToday = $customer->attendances()
                ->whereDate('checked_in_at', now()->toDateString())
                ->whereNotNull('checked_out_at')
                ->exists();

            $store = current_store();
            if ($store) {
                $attendanceIsOpen = $attendanceService->isWithinOperatingHours($store);
                $token = $attendanceService->getActiveToken($store, false);
                $attendanceQrPayload = $token ? $attendanceService->buildQrPayload($token) : null;
            }
        }

        return view('portal.dashboard', compact(
            'customer',
            'upcomingAppointments',
            'recentTreatments',
            'activePackages',
            'attendanceHistory',
            'activeAttendance',
            'attendanceQrPayload',
            'attendanceIsOpen',
            'hasCompletedToday'
        ));
    }

    public function profile(): View
    {
        $customer = $this->customer();

        return view('portal.profile', compact('customer'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $customer = $this->customer();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'birthdate' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $customer->update($validated);

        return back()->with('success', __('portal.profile_updated'));
    }

    public function appointments(): View
    {
        $customer = $this->customer();

        $appointments = $customer->appointments()
            ->with(['service', 'staff'])
            ->latest('appointment_date')
            ->paginate(10);

        return view('portal.appointments', compact('appointments'));
    }

    public function appointmentDetail(int $id): View
    {
        $customer = $this->customer();

        $appointment = $customer->appointments()
            ->with(['service', 'staff'])
            ->findOrFail($id);

        return view('portal.appointment-detail', compact('appointment'));
    }

    public function treatments(): View
    {
        $customer = $this->customer();

        $treatments = $customer->treatmentRecords()
            ->with(['appointment.service', 'staff'])
            ->latest('created_at')
            ->paginate(10);

        return view('portal.treatments', compact('treatments'));
    }

    public function treatmentDetail(int $id): View
    {
        $customer = $this->customer();

        $treatment = $customer->treatmentRecords()
            ->with(['appointment.service', 'staff'])
            ->findOrFail($id);

        return view('portal.treatment-detail', compact('treatment'));
    }

    public function packages(): View
    {
        $customer = $this->customer();

        $packages = $customer->packages()
            ->with('package')
            ->latest()
            ->paginate(10);

        return view('portal.packages', compact('packages'));
    }

    public function packageDetail(int $id): View
    {
        $customer = $this->customer();

        $customerPackage = $customer->packages()
            ->with(['package', 'usages.treatmentRecord'])
            ->findOrFail($id);

        return view('portal.package-detail', compact('customerPackage'));
    }

    public function loyalty(): View
    {
        $customer = $this->customer();

        $pointHistory = $customer->loyaltyPoints()
            ->with('transaction')
            ->latest()
            ->paginate(10);

        $availableRewards = LoyaltyReward::query()
            ->active()
            ->where('points_required', '<=', $customer->loyalty_points)
            ->orderBy('points_required')
            ->get();

        $pendingRedemptions = $customer->pendingRedemptions()
            ->with('reward')
            ->get();

        return view('portal.loyalty', compact(
            'customer',
            'pointHistory',
            'availableRewards',
            'pendingRedemptions'
        ));
    }

    public function transactions(): View
    {
        $customer = $this->customer();

        $transactions = $customer->transactions()
            ->with('items.service')
            ->latest()
            ->paginate(10);

        return view('portal.transactions', compact('transactions'));
    }

    public function transactionDetail(int $id): View
    {
        $customer = $this->customer();

        $transaction = $customer->transactions()
            ->with(['items.service', 'items.product', 'items.package', 'cashier'])
            ->findOrFail($id);

        return view('portal.transaction-detail', compact('transaction'));
    }
}
