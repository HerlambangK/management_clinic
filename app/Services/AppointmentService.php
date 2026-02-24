<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\OperatingHour;
use App\Models\Service;
use App\Models\Setting;
use Carbon\Carbon;

class AppointmentService
{
    /**
     * @return array<string>
     */
    public function getAvailableSlots(Carbon $date, int $serviceId, ?int $staffId = null, ?int $excludeAppointmentId = null): array
    {
        $service = Service::find($serviceId);
        if (! $service) {
            return [];
        }

        $operatingHours = OperatingHour::where('day_of_week', $date->dayOfWeek)->first();
        if (! $operatingHours || $operatingHours->is_closed) {
            return [];
        }

        $slotDuration = (int) Setting::get('slot_duration', 30);
        $openTime = Carbon::parse($date->toDateString().' '.$operatingHours->open_time);
        $closeTime = Carbon::parse($date->toDateString().' '.$operatingHours->close_time);

        $slots = [];
        $current = $openTime->copy();

        while ($current->copy()->addMinutes($service->duration_minutes)->lte($closeTime)) {
            $isAvailable = ! $this->hasConflict(
                $date,
                $current,
                $service->duration_minutes,
                $staffId,
                $excludeAppointmentId
            );

            if ($isAvailable) {
                $slots[] = $current->format('H:i');
            }

            $current->addMinutes($slotDuration);
        }

        return $slots;
    }

    public function hasConflict(Carbon $date, Carbon $time, int $duration, ?int $staffId = null, ?int $excludeAppointmentId = null): bool
    {
        $endTime = $time->copy()->addMinutes($duration);

        $query = Appointment::where('appointment_date', $date->toDateString())
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->where(function ($q) use ($time, $endTime) {
                $q->where(function ($q2) use ($time, $endTime) {
                    $q2->where('start_time', '<', $endTime->format('H:i:s'))
                        ->where('end_time', '>', $time->format('H:i:s'));
                });
            });

        if ($staffId) {
            $query->where('staff_id', $staffId);
        }

        if ($excludeAppointmentId) {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        return $query->exists();
    }

    public function calculateEndTime(string $startTime, int $durationMinutes): string
    {
        return Carbon::parse($startTime)->addMinutes($durationMinutes)->format('H:i:s');
    }

    /**
     * @return array<string, mixed>
     */
    public function getCalendarEvents(Carbon $startDate, Carbon $endDate, ?int $staffId = null): array
    {
        $query = Appointment::with(['customer', 'service', 'staff'])
            ->whereBetween('appointment_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->whereNotIn('status', ['cancelled']);

        if ($staffId) {
            $query->where('staff_id', $staffId);
        }

        $appointments = $query->get();

        return $appointments->map(function ($appointment) {
            $statusColors = [
                'pending' => '#9CA3AF',
                'confirmed' => '#FBBF24',
                'in_progress' => '#3B82F6',
                'completed' => '#10B981',
                'cancelled' => '#EF4444',
                'no_show' => '#EF4444',
            ];

            return [
                'id' => $appointment->id,
                'title' => $appointment->customer->name.' - '.$appointment->service->name,
                'start' => $appointment->appointment_date->format('Y-m-d').'T'.$appointment->start_time,
                'end' => $appointment->appointment_date->format('Y-m-d').'T'.$appointment->end_time,
                'color' => $statusColors[$appointment->status] ?? '#9CA3AF',
                'extendedProps' => [
                    'status' => $appointment->status,
                    'customer' => [
                        'id' => $appointment->customer->id,
                        'name' => $appointment->customer->name,
                        'phone' => $appointment->customer->phone,
                    ],
                    'service' => [
                        'id' => $appointment->service->id,
                        'name' => $appointment->service->name,
                    ],
                    'staff' => $appointment->staff ? [
                        'id' => $appointment->staff->id,
                        'name' => $appointment->staff->name,
                    ] : null,
                ],
            ];
        })->toArray();
    }
}
