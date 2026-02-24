<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // Mark setup as completed to prevent redirect
        Setting::set('setup_completed', true, 'boolean');
        Setting::set('business_type', 'clinic', 'string');
        Setting::set('business_name', 'Test Clinic', 'string');

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_setup_page_is_shown_when_setup_not_completed(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('setup.index'));
    }
}
