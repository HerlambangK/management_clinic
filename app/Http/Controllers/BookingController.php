<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicBookingRequest;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Store;
use App\Models\User;
use App\Services\AppointmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        private AppointmentService $appointmentService
    ) {}

    /**
     * Show the public booking page.
     */
    public function index(): View
    {
        $this->storeBusinessTypeFilter(request());

        $categories = ServiceCategory::query()
            ->active()
            ->ordered()
            ->with(['services' => fn ($q) => $q->where('is_active', true)->orderBy('name')])
            ->get();

        $beauticians = User::query()
            ->where('role', 'beautician')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Get logged in customer if any
        $loggedInCustomer = Auth::guard('customer')->user();

        return view('booking.index', compact('categories', 'beauticians', 'loggedInCustomer'));
    }

    /**
     * Get available time slots for a given date and optional service/staff.
     */
    public function slots(Request $request): JsonResponse
    {
        $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'service_id' => ['nullable', 'exists:services,id'],
            'staff_id' => ['nullable', 'exists:users,id'],
        ]);

        $date = \Carbon\Carbon::parse($request->input('date'));
        $serviceId = $request->input('service_id');
        $staffId = $request->input('staff_id');

        $slots = $this->appointmentService->getAvailableSlots($date, $serviceId, $staffId);

        // Group slots by morning/afternoon
        $morning = [];
        $afternoon = [];

        foreach ($slots as $slot) {
            $hour = (int) substr($slot, 0, 2);
            if ($hour < 12) {
                $morning[] = $slot;
            } else {
                $afternoon[] = $slot;
            }
        }

        return response()->json([
            'slots' => $slots,
            'morning' => $morning,
            'afternoon' => $afternoon,
        ]);
    }

    /**
     * Store a new booking from the public form.
     */
    public function store(PublicBookingRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $store = $this->resolveBookingStore();

        // Find referrer if referral code provided
        $referrerId = null;
        if (! empty($validated['referral_code']) && config('referral.enabled', true)) {
            $referrer = Customer::where('referral_code', $validated['referral_code'])->first();
            if ($referrer) {
                $referrerId = $referrer->id;
            }
        }

        // Find or create customer by phone
        $customerAttributes = ['phone' => $validated['phone']];
        if ($store) {
            $customerAttributes['store_id'] = $store->id;
        }

        $customer = Customer::firstOrCreate($customerAttributes, [
            'store_id' => $store?->id,
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'referred_by_id' => $referrerId,
        ]);

        // Update customer name/email if they already exist
        if ($customer->wasRecentlyCreated === false) {
            $updates = [
                'name' => $validated['name'],
                'email' => $validated['email'] ?? $customer->email,
            ];

            if (! $customer->store_id && $store) {
                $updates['store_id'] = $store->id;
            }

            $customer->update($updates);
        }

        // Get service duration
        $service = Service::find($validated['service_id']);
        $endTime = $this->appointmentService->calculateEndTime(
            $validated['start_time'],
            $service->duration_minutes
        );

        // Create appointment
        $appointment = Appointment::create([
            'store_id' => $store?->id,
            'customer_id' => $customer->id,
            'service_id' => $validated['service_id'],
            'staff_id' => $validated['staff_id'] ?? null,
            'appointment_date' => $validated['appointment_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $endTime,
            'status' => 'pending',
            'source' => 'online',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('booking.confirmation', $appointment)
            ->with('success', __('booking.success'));
    }

    /**
     * Show booking confirmation page.
     */
    public function confirmation(Appointment $appointment): View
    {
        $appointment->load(['customer', 'service', 'staff']);

        return view('booking.confirmation', compact('appointment'));
    }

    /**
     * Check booking status by phone number.
     */
    public function checkStatus(Request $request): View
    {
        $appointments = collect();

        if ($request->filled('phone')) {
            $customer = Customer::where('phone', $request->phone)->first();

            if ($customer) {
                $appointments = Appointment::query()
                    ->where('customer_id', $customer->id)
                    ->where('appointment_date', '>=', now()->toDateString())
                    ->with(['service', 'staff'])
                    ->orderBy('appointment_date')
                    ->orderBy('start_time')
                    ->limit(10)
                    ->get();
            }
        }

        return view('booking.status', compact('appointments'));
    }

    /**
     * Cancel a booking (by customer via confirmation link).
     */
    public function cancel(Request $request, Appointment $appointment): RedirectResponse
    {
        // Only allow cancellation of pending/confirmed appointments
        if (! in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->with('error', __('booking.cannot_cancel'));
        }

        // Only allow cancellation at least 2 hours before appointment
        $appointmentDateTime = \Carbon\Carbon::parse(
            $appointment->appointment_date->format('Y-m-d').' '.$appointment->start_time
        );
        if (now()->diffInHours($appointmentDateTime, false) < 2) {
            return back()->with('error', __('booking.cancel_too_late'));
        }

        $appointment->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_reason' => __('booking.cancelled_by_customer'),
        ]);

        return redirect()
            ->route('booking.index')
            ->with('success', __('booking.cancelled_success'));
    }

    private function resolveBookingStore(): ?Store
    {
        $store = current_store();

        if ($store) {
            return $store;
        }

        $businessType = $this->resolveBusinessTypeFilter(request());

        $customer = Auth::guard('customer')->user();

        if ($customer?->store_id) {
            $customerStore = Store::query()->find($customer->store_id);

            if ($customerStore && (! $businessType || $customerStore->business_type === $businessType)) {
                return $customerStore;
            }
        }

        $query = Store::query()
            ->active()
            ->approved()
            ->orderBy('id');

        if ($businessType) {
            $query->where('business_type', $businessType);
        }

        return $query->first();
    }

    private function resolveBusinessTypeFilter(Request $request, ?string $default = null): ?string
    {
        $businessTypes = array_keys(config('business.types', []));
        $input = $request->input('business_type')
            ?? $request->input('type')
            ?? $request->query('business_type')
            ?? $request->query('type')
            ?? ($request->hasSession() ? $request->session()->get('business_type_filter') : null)
            ?? $default;

        if ($input && in_array($input, $businessTypes, true)) {
            return $input;
        }

        return $default;
    }

    private function storeBusinessTypeFilter(Request $request): void
    {
        $businessType = $this->resolveBusinessTypeFilter($request);

        if ($businessType && $request->hasSession()) {
            $request->session()->put('business_type_filter', $businessType);
        }
    }
}
