<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Mark setup as completed to access booking routes
        Setting::set('setup_completed', true, 'boolean');
        Setting::set('business_type', 'clinic', 'string');
        Setting::set('business_name', 'Test Clinic', 'string');
    }

    public function test_booking_index_page_is_accessible(): void
    {
        // Create some service categories and services
        $category = ServiceCategory::factory()->create(['is_active' => true]);
        Service::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
        ]);

        $response = $this->get(route('booking.index'));

        $response->assertStatus(200);
        $response->assertViewIs('booking.index');
    }

    public function test_booking_slots_returns_available_slots(): void
    {
        // Create a service for the slots query
        $category = ServiceCategory::factory()->create(['is_active' => true]);
        $service = Service::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'duration_minutes' => 60,
        ]);

        // Create operating hours for all days
        for ($day = 0; $day <= 6; $day++) {
            \App\Models\OperatingHour::create([
                'day_of_week' => $day,
                'open_time' => '09:00',
                'close_time' => '17:00',
                'is_closed' => false,
            ]);
        }

        $tomorrow = now()->addDay()->format('Y-m-d');

        $response = $this->getJson(route('booking.slots', [
            'date' => $tomorrow,
            'service_id' => $service->id,
        ]));

        $response->assertStatus(200);
        $response->assertJsonStructure(['slots', 'morning', 'afternoon']);
    }

    public function test_booking_slots_requires_valid_date(): void
    {
        $response = $this->getJson(route('booking.slots', ['date' => 'invalid-date']));

        $response->assertStatus(422);
    }

    public function test_booking_store_creates_appointment(): void
    {
        $category = ServiceCategory::factory()->create(['is_active' => true]);
        $service = Service::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'duration_minutes' => 60,
        ]);

        $tomorrow = now()->addDay()->format('Y-m-d');

        $response = $this->post(route('booking.store'), [
            'name' => 'Test Customer',
            'phone' => '081234567890',
            'email' => 'customer@test.com',
            'service_id' => $service->id,
            'appointment_date' => $tomorrow,
            'start_time' => '10:00',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check customer created
        $this->assertDatabaseHas('customers', [
            'name' => 'Test Customer',
            'phone' => '081234567890',
        ]);

        // Check appointment created
        $this->assertDatabaseHas('appointments', [
            'service_id' => $service->id,
            'status' => 'pending',
            'source' => 'online',
        ]);
    }

    public function test_booking_store_uses_existing_customer(): void
    {
        $customer = Customer::factory()->create([
            'phone' => '081234567890',
            'name' => 'Old Name',
        ]);

        $category = ServiceCategory::factory()->create(['is_active' => true]);
        $service = Service::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'duration_minutes' => 60,
        ]);

        $tomorrow = now()->addDay()->format('Y-m-d');

        $this->post(route('booking.store'), [
            'name' => 'Updated Name',
            'phone' => '081234567890',
            'service_id' => $service->id,
            'appointment_date' => $tomorrow,
            'start_time' => '10:00',
        ]);

        // Customer should be updated, not duplicated
        $this->assertDatabaseCount('customers', 1);
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_booking_store_validates_required_fields(): void
    {
        $response = $this->post(route('booking.store'), []);

        $response->assertSessionHasErrors(['name', 'phone', 'service_id', 'appointment_date', 'start_time']);
    }

    public function test_booking_confirmation_page_shows_appointment(): void
    {
        $customer = Customer::factory()->create();
        $category = ServiceCategory::factory()->create();
        $service = Service::factory()->create(['category_id' => $category->id]);
        $appointment = Appointment::factory()->create([
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'status' => 'pending',
        ]);

        $response = $this->get(route('booking.confirmation', $appointment));

        $response->assertStatus(200);
        $response->assertViewIs('booking.confirmation');
        $response->assertViewHas('appointment');
    }

    public function test_booking_status_page_is_accessible(): void
    {
        $response = $this->get(route('booking.status'));

        $response->assertStatus(200);
        $response->assertViewIs('booking.status');
    }

    public function test_booking_status_shows_customer_appointments(): void
    {
        $customer = Customer::factory()->create(['phone' => '081234567890']);
        $category = ServiceCategory::factory()->create();
        $service = Service::factory()->create(['category_id' => $category->id]);
        Appointment::factory()->create([
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'appointment_date' => now()->addDay()->format('Y-m-d'),
            'status' => 'pending',
        ]);

        $response = $this->get(route('booking.status', ['phone' => '081234567890']));

        $response->assertStatus(200);
        $response->assertViewHas('appointments');
    }

    public function test_booking_cancel_cancels_pending_appointment(): void
    {
        $customer = Customer::factory()->create();
        $category = ServiceCategory::factory()->create();
        $service = Service::factory()->create(['category_id' => $category->id]);
        $appointment = Appointment::factory()->create([
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'appointment_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '10:00',
            'status' => 'pending',
        ]);

        $response = $this->post(route('booking.cancel', $appointment));

        $response->assertRedirect(route('booking.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_booking_cancel_fails_for_completed_appointment(): void
    {
        $customer = Customer::factory()->create();
        $category = ServiceCategory::factory()->create();
        $service = Service::factory()->create(['category_id' => $category->id]);
        $appointment = Appointment::factory()->create([
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'status' => 'completed',
        ]);

        $response = $this->post(route('booking.cancel', $appointment));

        $response->assertSessionHas('error');

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'completed',
        ]);
    }

    public function test_booking_cancel_fails_if_too_late(): void
    {
        $customer = Customer::factory()->create();
        $category = ServiceCategory::factory()->create();
        $service = Service::factory()->create(['category_id' => $category->id]);

        // Create appointment starting in 1 hour (less than 2 hour policy)
        $appointment = Appointment::factory()->create([
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'appointment_date' => now()->format('Y-m-d'),
            'start_time' => now()->addHour()->format('H:i'),
            'status' => 'pending',
        ]);

        $response = $this->post(route('booking.cancel', $appointment));

        $response->assertSessionHas('error');

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'pending',
        ]);
    }

    public function test_booking_redirects_to_setup_when_not_completed(): void
    {
        // Remove setup completed flag
        Setting::query()->where('key', 'setup_completed')->delete();

        $response = $this->get(route('booking.index'));

        $response->assertRedirect(route('setup.index'));
    }
}
