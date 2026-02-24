# Phase 5: Appointment System

## Overview
Booking management dengan calendar view, status tracking, dan staff assignment.

---

## 5.1 Appointment Calendar

### Tasks
- [ ] Create Appointment model & migration
- [ ] Calendar view (daily default, weekly optional)
- [ ] Staff column view
- [ ] Color coded by status
- [ ] Click to view/edit appointment

### Library
Using FullCalendar.js untuk calendar view

### Files to Create
```
app/Models/Appointment.php
app/Http/Controllers/AppointmentController.php
app/Http/Requests/AppointmentRequest.php
database/migrations/xxxx_create_appointments_table.php
resources/views/appointments/index.blade.php
resources/views/appointments/calendar.blade.php
resources/views/appointments/create.blade.php
resources/views/appointments/show.blade.php
```

### Fields
| Field | Type | Required | Notes |
|-------|------|----------|-------|
| customer_id | foreign | Yes | |
| service_id | foreign | Yes | |
| staff_id | foreign | No | Beautician assigned |
| customer_package_id | foreign | No | If redeemed from package |
| appointment_date | date | Yes | |
| start_time | time | Yes | |
| end_time | time | Yes | Calculated from service duration |
| status | enum | Yes | pending/confirmed/in_progress/completed/cancelled/no_show |
| source | enum | Yes | walk_in/phone/whatsapp/online |
| notes | text | No | |
| cancelled_at | timestamp | No | |
| cancelled_reason | text | No | |

### UI - Calendar View
```
┌─────────────────────────────────────────────────────────────┐
│ Appointments                               [+ New Booking]  │
├─────────────────────────────────────────────────────────────┤
│ [Today] [Day] [Week]        < January 26, 2026 >           │
├─────────────────────────────────────────────────────────────┤
│ Time    │ Maya           │ Dr. Sarah      │ Lisa           │
│─────────┼────────────────┼────────────────┼────────────────│
│ 08:00   │                │                │                │
│ 08:30   │                │                │                │
│ 09:00   │ ██████████████ │                │ ██████████████ │
│         │ Rina           │                │ Dewi           │
│ 09:30   │ Facial Bright  │                │ Facial Acne    │
│ 10:00   │ ██████████████ │ ██████████████ │ ██████████████ │
│         │                │ Siti           │                │
│ 10:30   │                │ Laser Toning   │                │
│ 11:00   │                │ ██████████████ │                │
│         │                │                │                │
│ 11:30   │                │                │                │
│ 12:00   │ ─── BREAK ──── │ ─── BREAK ──── │ ─── BREAK ──── │
│ 12:30   │ ─── BREAK ──── │ ─── BREAK ──── │ ─── BREAK ──── │
│ 13:00   │ ██████████████ │                │                │
│         │ Anisa          │                │                │
│ 13:30   │ Body Scrub     │                │                │
└─────────┴────────────────┴────────────────┴────────────────┘

Legend:
🟢 Completed  🔵 In Progress  🟡 Confirmed  ⚪ Pending  🔴 Cancelled
```

---

## 5.2 Create Appointment

### Tasks
- [ ] Multi-step booking form
- [ ] Customer search/select/create
- [ ] Service selection
- [ ] Available slots calculation
- [ ] Staff assignment (optional)
- [ ] Package redemption option
- [ ] Booking confirmation

### UI - Booking Form (Steps)

#### Step 1: Select Customer
```
┌─────────────────────────────────────────────────────────────┐
│ New Booking - Step 1 of 4                                   │
│ SELECT CUSTOMER                                             │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ 🔍 Search customer by name or phone...                     │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ Recent Customers:                                       ││
│ │                                                         ││
│ │ ○ Rina Wijaya        0812-3456-7890    Last: 2 days    ││
│ │ ○ Siti Aminah        0813-9876-5432    Last: 1 week    ││
│ │ ○ Dewi Kartika       0815-1234-5678    Last: Today     ││
│ │                                                         ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│ Can't find customer?  [+ Add New Customer]                 │
│                                                             │
│                                            [Next →]        │
└─────────────────────────────────────────────────────────────┘
```

#### Step 2: Select Service
```
┌─────────────────────────────────────────────────────────────┐
│ New Booking - Step 2 of 4                                   │
│ SELECT SERVICE                                              │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ Customer: Rina Wijaya                                       │
│                                                             │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ 💆 FACIAL                                               ││
│ │ ○ Facial Brightening      60 min    Rp 250.000         ││
│ │ ○ Facial Acne Treatment   90 min    Rp 350.000         ││
│ │ ○ Facial Anti Aging       75 min    Rp 400.000         ││
│ │                                                         ││
│ │ 🧴 BODY TREATMENT                                       ││
│ │ ○ Body Scrub              60 min    Rp 300.000         ││
│ │ ○ Body Massage            90 min    Rp 400.000         ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│ ─── OR REDEEM FROM PACKAGE ───                             │
│ ☐ Facial Glow Package (6 sessions remaining)               │
│                                                             │
│                                   [← Back]  [Next →]       │
└─────────────────────────────────────────────────────────────┘
```

#### Step 3: Select Date & Time
```
┌─────────────────────────────────────────────────────────────┐
│ New Booking - Step 3 of 4                                   │
│ SELECT DATE & TIME                                          │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ Customer: Rina Wijaya                                       │
│ Service: Facial Brightening (60 min)                        │
│                                                             │
│ Date: [📅 January 27, 2026                            ▼]   │
│                                                             │
│ Available Slots:                                            │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ MORNING                                                 ││
│ │ ○ 09:00  ○ 09:30  ● 10:00  ○ 10:30  ○ 11:00           ││
│ │                                                         ││
│ │ AFTERNOON                                               ││
│ │ ○ 13:00  ○ 13:30  ○ 14:00  ○ 14:30  ○ 15:00           ││
│ │ ○ 15:30  ○ 16:00  ○ 16:30                              ││
│ │                                                         ││
│ │ ❌ Greyed slots = not available                         ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│ Beautician: [Any Available ▼]                              │
│             ○ Maya  ○ Lisa  ○ Dr. Sarah                   │
│                                                             │
│                                   [← Back]  [Next →]       │
└─────────────────────────────────────────────────────────────┘
```

#### Step 4: Confirmation
```
┌─────────────────────────────────────────────────────────────┐
│ New Booking - Step 4 of 4                                   │
│ CONFIRMATION                                                │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ BOOKING SUMMARY                                         ││
│ │                                                         ││
│ │ Customer:   Rina Wijaya                                 ││
│ │ Phone:      0812-3456-7890                              ││
│ │                                                         ││
│ │ Service:    Facial Brightening                          ││
│ │ Duration:   60 minutes                                  ││
│ │ Price:      Rp 250.000                                  ││
│ │                                                         ││
│ │ Date:       Monday, 27 January 2026                     ││
│ │ Time:       10:00 - 11:00                               ││
│ │ Beautician: Maya                                        ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│ Notes (optional):                                           │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ Customer request extra masker                          ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│                           [← Back]  [Confirm Booking]      │
└─────────────────────────────────────────────────────────────┘
```

---

## 5.3 Appointment Status Flow

### Status Transitions
```
pending → confirmed → in_progress → completed
    ↓         ↓            ↓
cancelled  cancelled   cancelled

pending → no_show (if customer doesn't come)
```

### Actions per Status
| Status | Available Actions |
|--------|-------------------|
| pending | Confirm, Cancel |
| confirmed | Start, Cancel, No Show |
| in_progress | Complete, Cancel |
| completed | Create Treatment Record, Checkout |
| cancelled | - |
| no_show | - |

---

## 5.4 Appointment Detail Modal

```
┌─────────────────────────────────────────────────────────────┐
│ Appointment Detail                                    [×]   │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ Status: 🟡 Confirmed                                        │
│                                                             │
│ ┌─────────────────────────────────────────────────────────┐│
│ │ Customer:   Rina Wijaya                                 ││
│ │ Phone:      0812-3456-7890                 [View Profile]││
│ │                                                         ││
│ │ Service:    Facial Brightening                          ││
│ │ Duration:   60 minutes                                  ││
│ │                                                         ││
│ │ Date:       Monday, 27 January 2026                     ││
│ │ Time:       10:00 - 11:00                               ││
│ │ Beautician: Maya                                        ││
│ │                                                         ││
│ │ Source:     WhatsApp                                    ││
│ │ Notes:      Customer request extra masker               ││
│ │                                                         ││
│ │ Created:    26 Jan 2026, 14:30                          ││
│ └─────────────────────────────────────────────────────────┘│
│                                                             │
│ Actions:                                                    │
│ [Start Treatment] [Reschedule] [Cancel]                    │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 5.5 Available Slots Calculation

```php
// AppointmentService.php

public function getAvailableSlots(Carbon $date, int $serviceId, ?int $staffId = null): array
{
    $service = Service::find($serviceId);
    $operatingHours = OperatingHour::where('day_of_week', $date->dayOfWeek)->first();

    if ($operatingHours->is_closed) {
        return [];
    }

    $slotDuration = Setting::get('slot_duration', 30); // minutes
    $openTime = Carbon::parse($operatingHours->open_time);
    $closeTime = Carbon::parse($operatingHours->close_time);

    $slots = [];
    $current = $openTime->copy();

    while ($current->copy()->addMinutes($service->duration_minutes)->lte($closeTime)) {
        // Check if slot is available
        $isAvailable = !$this->hasConflict($date, $current, $service->duration_minutes, $staffId);

        if ($isAvailable) {
            $slots[] = $current->format('H:i');
        }

        $current->addMinutes($slotDuration);
    }

    return $slots;
}

private function hasConflict(Carbon $date, Carbon $time, int $duration, ?int $staffId): bool
{
    $query = Appointment::where('appointment_date', $date->toDateString())
        ->whereNotIn('status', ['cancelled', 'no_show'])
        ->where(function ($q) use ($time, $duration) {
            $endTime = $time->copy()->addMinutes($duration);
            $q->where(function ($q2) use ($time, $endTime) {
                $q2->where('start_time', '<', $endTime->format('H:i:s'))
                   ->where('end_time', '>', $time->format('H:i:s'));
            });
        });

    if ($staffId) {
        $query->where('staff_id', $staffId);
    }

    return $query->exists();
}
```

---

## API Endpoints

```php
// Get appointments for calendar
GET /api/appointments?start_date=2026-01-01&end_date=2026-01-31&staff_id=1
Response: {
    data: [
        {
            id, title, start, end, color, status,
            customer: { id, name },
            service: { id, name },
            staff: { id, name }
        }
    ]
}

// Get available slots
GET /api/appointments/available-slots?date=2026-01-27&service_id=1&staff_id=1
Response: {
    data: ["09:00", "09:30", "10:00", ...]
}

// Update status
PATCH /api/appointments/{id}/status
Body: { status: "confirmed" }
Response: { data: { id, status, ... } }
```

---

## Routes

```php
// routes/web.php
Route::resource('appointments', AppointmentController::class);
Route::get('appointments/calendar', [AppointmentController::class, 'calendar'])->name('appointments.calendar');

// routes/api.php
Route::get('appointments', [AppointmentApiController::class, 'index']);
Route::get('appointments/available-slots', [AppointmentApiController::class, 'availableSlots']);
Route::patch('appointments/{appointment}/status', [AppointmentApiController::class, 'updateStatus']);
```

---

## Acceptance Criteria

- [ ] Calendar view menampilkan appointments per hari/minggu
- [ ] Color coded by status
- [ ] Click appointment membuka detail modal
- [ ] Create booking dengan multi-step form
- [ ] Customer search/select works
- [ ] Service selection works
- [ ] Available slots calculated correctly
- [ ] Staff assignment optional
- [ ] Package redemption works
- [ ] Status update works (confirm, start, complete, cancel)
- [ ] Conflict detection prevents double booking
