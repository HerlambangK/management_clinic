<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberAttendanceStoreRequest;
use App\Models\MemberAttendance;
use App\Models\Store;
use App\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberAttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $query = MemberAttendance::query()
            ->with('customer')
            ->latest('checked_in_at');

        if ($request->filled('date')) {
            $query->whereDate('checked_in_at', $request->input('date'));
        }

        $attendances = $query->paginate(30)->withQueryString();

        return view('attendances.index', compact('attendances'));
    }

    public function qr(AttendanceService $attendanceService): View
    {
        $store = current_store();
        $token = $store ? $attendanceService->getActiveToken($store, true) : null;
        $qrPayload = $token ? $attendanceService->buildQrPayload($token) : null;
        $isOpen = $store ? $attendanceService->isWithinOperatingHours($store) : false;
        $publicUrl = $store && $token ? route('attendance.public-qr', $token) : null;

        return view('attendances.qr', compact('qrPayload', 'store', 'isOpen', 'publicUrl'));
    }

    public function scan(Request $request, AttendanceService $attendanceService): View
    {
        $customer = auth('customer')->user();
        $lastAttendance = $customer?->attendances()->latest('checked_in_at')->first();
        $activeAttendance = $customer?->attendances()->whereNull('checked_out_at')->latest('checked_in_at')->first();
        $historyQuery = $customer?->attendances()->latest('checked_in_at');
        $filters = [
            'q' => $request->input('q'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'status' => $request->input('status'),
        ];

        if ($historyQuery) {
            if (! empty($filters['date_from'])) {
                $historyQuery->whereDate('checked_in_at', '>=', $filters['date_from']);
            }

            if (! empty($filters['date_to'])) {
                $historyQuery->whereDate('checked_in_at', '<=', $filters['date_to']);
            }

            if (! empty($filters['status']) && in_array($filters['status'], ['checked_in', 'completed'], true)) {
                $historyQuery->where('status', $filters['status']);
            }

            if (! empty($filters['q'])) {
                $historyQuery->whereDate('checked_in_at', $filters['q']);
            }
        }

        $attendanceHistory = $historyQuery
            ? $historyQuery->paginate(10)->withQueryString()
            : collect();
        $hasCompletedToday = $customer?->attendances()
            ->whereDate('checked_in_at', now()->toDateString())
            ->whereNotNull('checked_out_at')
            ->exists() ?? false;
        $store = current_store();
        $attendanceToken = $store ? $attendanceService->getActiveToken($store, true) : null;
        $attendanceIsOpen = $store ? $attendanceService->isWithinOperatingHours($store) : false;
        $scanMode = request()->string('mode')->toString();

        return view('portal.attendance.scan', compact(
            'lastAttendance',
            'activeAttendance',
            'attendanceHistory',
            'attendanceToken',
            'attendanceIsOpen',
            'scanMode',
            'filters',
            'hasCompletedToday'
        ));
    }

    public function store(MemberAttendanceStoreRequest $request): RedirectResponse
    {
        $customer = $request->user('customer');
        $activeAttendance = $customer?->attendances()
            ->whereNull('checked_out_at')
            ->latest('checked_in_at')
            ->first();

        if ($activeAttendance) {
            $activeAttendance->update([
                'checked_out_at' => now(),
                'checkout_latitude' => $request->input('latitude'),
                'checkout_longitude' => $request->input('longitude'),
                'checkout_accuracy' => $request->input('accuracy'),
                'checkout_location_name' => $request->input('location_name'),
                'status' => 'completed',
            ]);

            return back()->with('success', __('attendance.checkout_success'));
        }

        MemberAttendance::create([
            'customer_id' => $customer->id,
            'checked_in_at' => now(),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'accuracy' => $request->input('accuracy'),
            'location_name' => $request->input('location_name'),
            'status' => 'checked_in',
        ]);

        return back()->with('success', __('attendance.checkin_success'));
    }

    public function publicQr(string $token): View
    {
        $attendanceService = app(AttendanceService::class);
        $randomToken = $attendanceService->extractToken($token);

        if (! $randomToken) {
            abort(404);
        }

        $store = Store::query()
            ->where('attendance_token', $randomToken)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->firstOrFail();

        $activeToken = $attendanceService->getActiveToken($store, false);
        $qrPayload = $activeToken ? $attendanceService->buildQrPayload($activeToken) : null;
        $isOpen = $attendanceService->isWithinOperatingHours($store);

        return view('attendances.public-qr', compact('qrPayload', 'store', 'isOpen'));
    }

    public function regenerateQr(AttendanceService $attendanceService): RedirectResponse
    {
        $store = current_store();

        if (! $store) {
            return back()->with('error', __('attendance.qr_missing'));
        }

        if (! $attendanceService->isWithinOperatingHours($store)) {
            return back()->with('error', __('attendance.qr_closed'));
        }

        $attendanceService->regenerateToken($store);

        return back()->with('success', __('attendance.qr_regenerated'));
    }
}
