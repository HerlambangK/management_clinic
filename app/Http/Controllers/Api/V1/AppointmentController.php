<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\OperatingHour;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AppointmentController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Appointment::query()->with(['customer', 'service', 'staff']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('appointment_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('appointment_date', '<=', $request->end_date);
        }

        if ($request->filled('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $perPage = $request->integer('per_page', 15);
        $appointments = $query->orderBy('appointment_date')
            ->orderBy('start_time')
            ->paginate($perPage);

        return AppointmentResource::collection($appointments);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'service_id' => ['required', 'exists:services,id'],
            'staff_id' => ['nullable', 'exists:users,id'],
            'customer_package_id' => ['nullable', 'exists:customer_packages,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'source' => ['nullable', 'in:walk_in,phone,whatsapp,online'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = $startTime->copy()->addMinutes($service->duration_minutes);

        $appointment = Appointment::create([
            ...$validated,
            'end_time' => $endTime->format('H:i'),
            'status' => 'pending',
            'source' => $validated['source'] ?? 'online',
        ]);

        $appointment->load(['customer', 'service', 'staff']);

        return response()->json([
            'message' => 'Appointment berhasil dibuat',
            'data' => new AppointmentResource($appointment),
        ], 201);
    }

    public function show(Appointment $appointment): JsonResponse
    {
        $appointment->load(['customer', 'service', 'staff', 'treatmentRecord']);

        return response()->json([
            'data' => new AppointmentResource($appointment),
        ]);
    }

    public function update(Request $request, Appointment $appointment): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => ['sometimes', 'required', 'exists:customers,id'],
            'service_id' => ['sometimes', 'required', 'exists:services,id'],
            'staff_id' => ['nullable', 'exists:users,id'],
            'appointment_date' => ['sometimes', 'required', 'date'],
            'start_time' => ['sometimes', 'required', 'date_format:H:i'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if (isset($validated['service_id']) || isset($validated['start_time'])) {
            $serviceId = $validated['service_id'] ?? $appointment->service_id;
            $service = Service::findOrFail($serviceId);
            $startTime = Carbon::parse($validated['start_time'] ?? $appointment->start_time);
            $validated['end_time'] = $startTime->copy()->addMinutes($service->duration_minutes)->format('H:i');
        }

        $appointment->update($validated);
        $appointment->load(['customer', 'service', 'staff']);

        return response()->json([
            'message' => 'Appointment berhasil diperbarui',
            'data' => new AppointmentResource($appointment),
        ]);
    }

    public function destroy(Appointment $appointment): JsonResponse
    {
        if (in_array($appointment->status, ['in_progress', 'completed'])) {
            return response()->json([
                'message' => 'Tidak dapat menghapus appointment yang sedang berlangsung atau sudah selesai',
            ], 422);
        }

        $appointment->delete();

        return response()->json([
            'message' => 'Appointment berhasil dihapus',
        ]);
    }

    public function updateStatus(Request $request, Appointment $appointment): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,in_progress,completed,cancelled,no_show'],
            'cancelled_reason' => ['required_if:status,cancelled', 'nullable', 'string', 'max:500'],
        ]);

        $updateData = ['status' => $validated['status']];

        if ($validated['status'] === 'cancelled') {
            $updateData['cancelled_at'] = now();
            $updateData['cancelled_reason'] = $validated['cancelled_reason'] ?? null;
        }

        $appointment->update($updateData);
        $appointment->load(['customer', 'service', 'staff']);

        return response()->json([
            'message' => 'Status appointment berhasil diperbarui',
            'data' => new AppointmentResource($appointment),
        ]);
    }

    public function today(Request $request): AnonymousResourceCollection
    {
        $query = Appointment::query()
            ->with(['customer', 'service', 'staff'])
            ->today()
            ->orderBy('start_time');

        if ($request->filled('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }

        return AppointmentResource::collection($query->get());
    }

    public function calendar(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $appointments = Appointment::query()
            ->with(['customer', 'service', 'staff'])
            ->whereDate('appointment_date', '>=', $request->start_date)
            ->whereDate('appointment_date', '<=', $request->end_date)
            ->get();

        $events = $appointments->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'title' => $appointment->customer->name.' - '.$appointment->service->name,
                'start' => $appointment->appointment_date->format('Y-m-d').'T'.$appointment->start_time,
                'end' => $appointment->appointment_date->format('Y-m-d').'T'.$appointment->end_time,
                'color' => $this->getStatusColor($appointment->status),
                'extendedProps' => [
                    'status' => $appointment->status,
                    'customer_id' => $appointment->customer_id,
                    'customer_name' => $appointment->customer->name,
                    'service_id' => $appointment->service_id,
                    'service_name' => $appointment->service->name,
                    'staff_id' => $appointment->staff_id,
                    'staff_name' => $appointment->staff?->name,
                ],
            ];
        });

        return response()->json([
            'data' => $events,
        ]);
    }

    public function availableSlots(Request $request): JsonResponse
    {
        $request->validate([
            'date' => ['required', 'date'],
            'service_id' => ['required', 'exists:services,id'],
            'staff_id' => ['nullable', 'exists:users,id'],
        ]);

        $date = Carbon::parse($request->date);
        $service = Service::findOrFail($request->service_id);
        $staffId = $request->staff_id;

        $operatingHour = OperatingHour::where('day_of_week', $date->dayOfWeek)->first();

        if (! $operatingHour || $operatingHour->is_closed) {
            return response()->json([
                'data' => [],
                'message' => 'Klinik tutup pada hari ini',
            ]);
        }

        $slotDuration = 30;
        $openTime = Carbon::parse($operatingHour->open_time);
        $closeTime = Carbon::parse($operatingHour->close_time);

        $slots = [];
        $current = $openTime->copy();

        while ($current->copy()->addMinutes($service->duration_minutes)->lte($closeTime)) {
            $endTime = $current->copy()->addMinutes($service->duration_minutes);

            $hasConflict = Appointment::where('appointment_date', $date->toDateString())
                ->whereNotIn('status', ['cancelled', 'no_show'])
                ->when($staffId, fn ($q) => $q->where('staff_id', $staffId))
                ->where(function ($query) use ($current, $endTime) {
                    $query->where(function ($q) use ($current, $endTime) {
                        $q->where('start_time', '<', $endTime->format('H:i:s'))
                            ->where('end_time', '>', $current->format('H:i:s'));
                    });
                })
                ->exists();

            if (! $hasConflict) {
                $slots[] = $current->format('H:i');
            }

            $current->addMinutes($slotDuration);
        }

        return response()->json([
            'data' => $slots,
        ]);
    }

    private function getStatusColor(string $status): string
    {
        return match ($status) {
            'pending' => '#9CA3AF',
            'confirmed' => '#FBBF24',
            'in_progress' => '#3B82F6',
            'completed' => '#10B981',
            'cancelled', 'no_show' => '#EF4444',
            default => '#9CA3AF',
        };
    }
}
