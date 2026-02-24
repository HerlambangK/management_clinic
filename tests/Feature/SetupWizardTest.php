<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetupWizardTest extends TestCase
{
    use RefreshDatabase;

    public function test_setup_index_page_is_accessible_when_not_setup(): void
    {
        $response = $this->get(route('setup.index'));

        $response->assertStatus(200);
        $response->assertViewIs('setup.index');
    }

    public function test_setup_index_redirects_to_dashboard_when_already_setup(): void
    {
        Setting::set('setup_completed', true, 'boolean');

        $user = User::factory()->create(['role' => 'owner']);

        $response = $this->actingAs($user)->get(route('setup.index'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_setup_details_requires_valid_business_type(): void
    {
        $response = $this->get(route('setup.details'));

        $response->assertRedirect(route('setup.index'));
    }

    public function test_setup_details_page_shows_form_for_valid_type(): void
    {
        $response = $this->get(route('setup.details', ['type' => 'clinic']));

        $response->assertStatus(200);
        $response->assertViewIs('setup.details');
    }

    public function test_setup_details_page_works_for_salon(): void
    {
        $response = $this->get(route('setup.details', ['type' => 'salon']));

        $response->assertStatus(200);
        $response->assertViewIs('setup.details');
    }

    public function test_setup_details_page_works_for_barbershop(): void
    {
        $response = $this->get(route('setup.details', ['type' => 'barbershop']));

        $response->assertStatus(200);
        $response->assertViewIs('setup.details');
    }

    public function test_store_details_validates_required_fields(): void
    {
        $response = $this->post(route('setup.storeDetails'), []);

        $response->assertSessionHasErrors(['business_type', 'business_name']);
    }

    public function test_store_details_validates_business_type(): void
    {
        $response = $this->post(route('setup.storeDetails'), [
            'business_type' => 'invalid_type',
            'business_name' => 'Test Business',
        ]);

        $response->assertSessionHasErrors(['business_type']);
    }

    public function test_store_details_saves_to_session_and_redirects(): void
    {
        $response = $this->post(route('setup.storeDetails'), [
            'business_type' => 'clinic',
            'business_name' => 'My Clinic',
            'business_phone' => '081234567890',
            'business_address' => 'Jl. Test No. 123',
        ]);

        $response->assertRedirect(route('setup.account'));
        $response->assertSessionHas('setup.business_type', 'clinic');
        $response->assertSessionHas('setup.business_name', 'My Clinic');
    }

    public function test_setup_account_requires_session_data(): void
    {
        $response = $this->get(route('setup.account'));

        $response->assertRedirect(route('setup.index'));
    }

    public function test_setup_account_shows_form_with_session_data(): void
    {
        $response = $this->withSession([
            'setup.business_type' => 'clinic',
            'setup.business_name' => 'Test Clinic',
        ])->get(route('setup.account'));

        $response->assertStatus(200);
        $response->assertViewIs('setup.account');
    }

    public function test_complete_setup_validates_required_fields(): void
    {
        $response = $this->withSession([
            'setup.business_type' => 'clinic',
            'setup.business_name' => 'Test Clinic',
        ])->post(route('setup.complete'), []);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_complete_setup_validates_password_confirmation(): void
    {
        $response = $this->withSession([
            'setup.business_type' => 'clinic',
            'setup.business_name' => 'Test Clinic',
        ])->post(route('setup.complete'), [
            'name' => 'Owner Name',
            'email' => 'owner@test.com',
            'password' => 'password123',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_complete_setup_creates_owner_and_settings(): void
    {
        $response = $this->withSession([
            'setup.business_type' => 'salon',
            'setup.business_name' => 'My Salon',
            'setup.business_phone' => '081234567890',
            'setup.business_address' => 'Jl. Salon No. 1',
        ])->post(route('setup.complete'), [
            'name' => 'Salon Owner',
            'email' => 'owner@salon.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));

        // Check user created
        $this->assertDatabaseHas('users', [
            'name' => 'Salon Owner',
            'email' => 'owner@salon.com',
            'role' => 'owner',
        ]);

        // Check settings saved
        $this->assertEquals('salon', Setting::get('business_type'));
        $this->assertEquals('My Salon', Setting::get('business_name'));
        $this->assertTrue(Setting::get('setup_completed'));
    }

    public function test_complete_setup_creates_sample_categories_and_services(): void
    {
        $this->withSession([
            'setup.business_type' => 'clinic',
            'setup.business_name' => 'Beauty Clinic',
        ])->post(route('setup.complete'), [
            'name' => 'Clinic Owner',
            'email' => 'owner@clinic.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Check categories created (from config/business.php)
        $this->assertDatabaseHas('service_categories', [
            'name' => 'Facial Treatment',
        ]);

        // Check services created (from config/business.php)
        $this->assertDatabaseHas('services', [
            'name' => 'Facial Brightening',
        ]);
    }

    public function test_complete_setup_logs_in_user(): void
    {
        $this->withSession([
            'setup.business_type' => 'barbershop',
            'setup.business_name' => 'Cool Barbershop',
        ])->post(route('setup.complete'), [
            'name' => 'Barber Owner',
            'email' => 'owner@barbershop.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
    }

    public function test_landing_page_redirects_to_setup_when_not_completed(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('setup.index'));
    }

    public function test_landing_page_shows_when_setup_completed(): void
    {
        Setting::set('setup_completed', true, 'boolean');
        Setting::set('business_type', 'clinic', 'string');
        Setting::set('business_name', 'Test Clinic', 'string');

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_dashboard_redirects_to_setup_when_not_completed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertRedirect(route('setup.index'));
    }
}
