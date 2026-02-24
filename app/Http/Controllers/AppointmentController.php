<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function __construct(
        private AppointmentService $appointmentService
    ) {}

    public function index(Request $request): View
    {
        $date = $request->filled('date')
            ? Carbon::parse($request->date)
            : today();

        $staffId = $request->filled('staff_id') ? (int) $request->staff_id : null;

        $query = Appointment::with(['customer', 'service', 'staff'])
            ->whereDate('appointment_date', $date)
            ->orderBy('start_time');

        if ($staffId) {
            $query->where('staff_id', $staffId);
        }

        $appointments = $query->get();

        $beauticians = User::where('role', 'beautician')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $todayStats = [
            'total' => Appointment::today()->count(),
            'pending' => Appointment::today()->where('status', 'pending')->count(),
            'confirmed' => Appointment::today()->where('status', 'confirmed')->count(),
            'in_progress' => Appointment::today()->where('status', 'in_progress')->count(),
            'completed' => Appointment::today()->where('status', 'completed')->count(),
        ];

        return view('appointments.index', compact('appointments', 'date', 'beauticians', 'staffId', 'todayStats'));
    }

    public function create(Request $request): View
    {
        $customers = Customer::orderBy('name')->get();
        $categories = ServiceCategory::with(['services' => function ($q) {
            $q->where('is_active', true)->orderBy('name');
        }])->orderBy('sort_order')->get();

        $beauticians = User::where('role', 'beautician')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $selectedCustomerId = $request->get('customer_id');
        $selectedDate = $request->get('date', today()->format('Y-m-d'));

        return view('appointments.create', compact('customers', 'categories', 'beauticians', 'selectedCustomerId', 'selectedDate'));
    }

    public function store(AppointmentRequest $request): RedirectResponse
    {
        $service = Service::findOrFail($request->service_id);
        $endTime = $this->appointmentService->calculateEndTime($request->start_time, $service->duration_minutes);

        $appointment = Appointment::create([
            'customer_id' => $request->customer_id,
            'service_id' => $request->service_id,
            'staff_id' => $request->staff_id,
            'appointment_date' => $request->appointment_date,
            'start_time' => $request->start_time,
            'end_time' => $endTime,
            'source' => $request->source ?? 'walk_in',
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment berhasil dibuat.');
    }

    public function show(Appointment $appointment): View
    {
        $appointment->load(['customer', 'service', 'staff', 'treatmentRecord.staff']);

        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment): View
    {
        $appointment->load(['customer', 'service', 'staff']);

        $customers = Customer::orderBy('name')->get();
        $categories = ServiceCategory::with(['services' => function ($q) {
            $q->where('is_active', true)->orderBy('name');
        }])->orderBy('sort_order')->get();

        $beauticians = User::where('role', 'beautician')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('appointments.edit', compact('appointment', 'customers', 'categories', 'beauticians'));
    }

    public function update(AppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        $service = Service::findOrFail($request->service_id);
        $endTime = $this->appointmentService->calculateEndTime($request->start_time, $service->duration_minutes);

        $appointment->update([
            'customer_id' => $request->customer_id,
            'service_id' => $request->service_id,
            'staff_id' => $request->staff_id,
            'appointment_date' => $request->appointment_date,
            'start_time' => $request->start_time,
            'end_time' => $endTime,
            'source' => $request->source,
            'notes' => $request->notes,
        ]);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment berhasil diperbarui.');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment berhasil dihapus.');
    }

    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:pending,confirmed,in_progress,completed,cancelled,no_show'],
            'cancelled_reason' => ['required_if:status,cancelled', 'nullable', 'string', 'max:500'],
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'cancelled') {
            $data['cancelled_at'] = now();
            $data['cancelled_reason'] = $request->cancelled_reason;
        }

        if ($request->status === 'completed') {
            $customer = $appointment->customer;
            $customer->increment('total_visits');
            $customer->update(['last_visit' => today()]);
        }

        $appointment->update($data);

        $statusLabels = Appointment::STATUSES;

        return back()->with('success', "Status berhasil diubah menjadi {$statusLabels[$request->status]}.");
    }

    public function getAvailableSlots(Request $request): JsonResponse
    {
        $request->validate([
            'date' => ['required', 'date'],
            'service_id' => ['required', 'exists:services,id'],
            'staff_id' => ['nullable', 'exists:users,id'],
            'exclude_appointment_id' => ['nullable', 'exists:appointments,id'],
        ]);

        $date = Carbon::parse($request->date);
        $slots = $this->appointmentService->getAvailableSlots(
            $date,
            (int) $request->service_id,
            $request->staff_id ? (int) $request->staff_id : null,
            $request->exclude_appointment_id ? (int) $request->exclude_appointment_id : null
        );

        return response()->json(['slots' => $slots]);
    }

    public function calendarEvents(Request $request): JsonResponse
    {
        $request->validate([
            'start' => ['required', 'date'],
            'end' => ['required', 'date'],
            'staff_id' => ['nullable', 'exists:users,id'],
        ]);

        $events = $this->appointmentService->getCalendarEvents(
            Carbon::parse($request->start),
            Carbon::parse($request->end),
            $request->staff_id ? (int) $request->staff_id : null
        );

        return response()->json($events);
    }
}
