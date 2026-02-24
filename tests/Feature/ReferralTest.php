<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\ReferralLog;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReferralTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::set('setup_completed', true, 'boolean');
        Setting::set('business_type', 'clinic', 'string');
        Setting::set('business_name', 'Test Clinic', 'string');
    }

    public function test_customer_gets_referral_code_on_creation(): void
    {
        $customer = Customer::factory()->create();

        $this->assertNotNull($customer->referral_code);
        $this->assertStringStartsWith('REF-', $customer->referral_code);
    }

    public function test_referral_code_is_unique(): void
    {
        $customer1 = Customer::factory()->create();
        $customer2 = Customer::factory()->create();

        $this->assertNotEquals($customer1->referral_code, $customer2->referral_code);
    }

    public function test_booking_with_valid_referral_code_links_customers(): void
    {
        $referrer = Customer::factory()->create();

        $category = ServiceCategory::factory()->create(['is_active' => true]);
        $service = Service::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'duration_minutes' => 60,
        ]);

        $tomorrow = now()->addDay()->format('Y-m-d');

        $response = $this->post(route('booking.store'), [
            'name' => 'New Customer',
            'phone' => '081234567899',
            'service_id' => $service->id,
            'appointment_date' => $tomorrow,
            'start_time' => '10:00',
            'referral_code' => $referrer->referral_code,
        ]);

        $response->assertRedirect();

        $newCustomer = Customer::where('phone', '081234567899')->first();

        $this->assertNotNull($newCustomer);
        $this->assertEquals($referrer->id, $newCustomer->referred_by_id);
        $this->assertNull($newCustomer->referral_rewarded_at);
    }

    public function test_booking_with_invalid_referral_code_is_ignored(): void
    {
        $category = ServiceCategory::factory()->create(['is_active' => true]);
        $service = Service::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'duration_minutes' => 60,
        ]);

        $tomorrow = now()->addDay()->format('Y-m-d');

        $response = $this->post(route('booking.store'), [
            'name' => 'New Customer',
            'phone' => '081234567888',
            'service_id' => $service->id,
            'appointment_date' => $tomorrow,
            'start_time' => '10:00',
            'referral_code' => 'INVALID-CODE',
        ]);

        $response->assertRedirect();

        $newCustomer = Customer::where('phone', '081234567888')->first();

        $this->assertNotNull($newCustomer);
        $this->assertNull($newCustomer->referred_by_id);
    }

    public function test_customer_cannot_use_own_referral_code(): void
    {
        $existingCustomer = Customer::factory()->create([
            'phone' => '081234567777',
        ]);

        $category = ServiceCategory::factory()->create(['is_active' => true]);
        $service = Service::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
            'duration_minutes' => 60,
        ]);

        $tomorrow = now()->addDay()->format('Y-m-d');

        // Existing customer tries to book with their own referral code
        $response = $this->post(route('booking.store'), [
            'name' => $existingCustomer->name,
            'phone' => '081234567777',
            'service_id' => $service->id,
            'appointment_date' => $tomorrow,
            'start_time' => '10:00',
            'referral_code' => $existingCustomer->referral_code,
        ]);

        $response->assertRedirect();

        $existingCustomer->refresh();

        // Should not link to self
        $this->assertNull($existingCustomer->referred_by_id);
    }

    public function test_referral_reward_given_on_first_paid_transaction(): void
    {
        config(['referral.enabled' => true]);
        config(['referral.referrer_bonus_points' => 100]);
        config(['referral.referee_bonus_points' => 50]);

        $referrer = Customer::factory()->create([
            'loyalty_points' => 0,
        ]);

        $referee = Customer::factory()->create([
            'referred_by_id' => $referrer->id,
            'referral_rewarded_at' => null,
            'loyalty_points' => 0,
        ]);

        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);

        // Create a transaction for the referee
        $transaction = Transaction::create([
            'customer_id' => $referee->id,
            'cashier_id' => $user->id,
            'subtotal' => 100000,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total_amount' => 100000,
            'paid_amount' => 0,
            'status' => 'pending',
        ]);

        // Pay the transaction
        $response = $this->post(route('transactions.pay', $transaction), [
            'amount' => 100000,
            'payment_method' => 'cash',
        ]);

        $referrer->refresh();
        $referee->refresh();

        // Referrer should get 100 points (referral only)
        $this->assertEquals(100, $referrer->loyalty_points);

        // Referee should get 50 points (referral) + transaction points
        // Transaction points: 100000 / 10000 = 10 points (based on loyalty config)
        // Total: 50 + 10 = 60 points
        $this->assertGreaterThanOrEqual(50, $referee->loyalty_points);

        // Referral should be marked as rewarded
        $this->assertNotNull($referee->referral_rewarded_at);

        // ReferralLog should be created
        $this->assertDatabaseHas('referral_logs', [
            'referrer_id' => $referrer->id,
            'referee_id' => $referee->id,
            'referrer_points' => 100,
            'referee_points' => 50,
            'transaction_id' => $transaction->id,
            'status' => 'rewarded',
        ]);
    }

    public function test_referral_reward_not_given_on_second_transaction(): void
    {
        config(['referral.enabled' => true]);
        config(['referral.referrer_bonus_points' => 100]);
        config(['referral.referee_bonus_points' => 50]);

        $referrer = Customer::factory()->create([
            'loyalty_points' => 100, // Already has points from first referral
        ]);

        $referee = Customer::factory()->create([
            'referred_by_id' => $referrer->id,
            'referral_rewarded_at' => now(), // Already rewarded
            'loyalty_points' => 50,
        ]);

        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);

        // Create a second transaction
        $transaction = Transaction::create([
            'customer_id' => $referee->id,
            'cashier_id' => $user->id,
            'subtotal' => 200000,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total_amount' => 200000,
            'paid_amount' => 0,
            'status' => 'pending',
        ]);

        $this->post(route('transactions.pay', $transaction), [
            'amount' => 200000,
            'payment_method' => 'cash',
        ]);

        $referrer->refresh();
        $referee->refresh();

        // Points should not increase from referral (might increase from transaction points)
        // But referral log should not be created
        $this->assertDatabaseMissing('referral_logs', [
            'transaction_id' => $transaction->id,
        ]);
    }

    public function test_referral_reward_not_given_when_disabled(): void
    {
        config(['referral.enabled' => false]);

        $referrer = Customer::factory()->create([
            'loyalty_points' => 0,
        ]);

        $referee = Customer::factory()->create([
            'referred_by_id' => $referrer->id,
            'referral_rewarded_at' => null,
            'loyalty_points' => 0,
        ]);

        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);

        $transaction = Transaction::create([
            'customer_id' => $referee->id,
            'cashier_id' => $user->id,
            'subtotal' => 100000,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total_amount' => 100000,
            'paid_amount' => 0,
            'status' => 'pending',
        ]);

        $this->post(route('transactions.pay', $transaction), [
            'amount' => 100000,
            'payment_method' => 'cash',
        ]);

        $referee->refresh();

        // Referral should not be marked as rewarded
        $this->assertNull($referee->referral_rewarded_at);

        // No referral log should be created
        $this->assertDatabaseMissing('referral_logs', [
            'referrer_id' => $referrer->id,
            'referee_id' => $referee->id,
        ]);
    }

    public function test_admin_can_view_referrals_page(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get(route('loyalty.referrals'));

        $response->assertStatus(200);
        $response->assertViewIs('loyalty.referrals');
    }

    public function test_referrals_page_shows_referral_data(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $referrer = Customer::factory()->create();
        $referee = Customer::factory()->create([
            'referred_by_id' => $referrer->id,
        ]);

        ReferralLog::create([
            'referrer_id' => $referrer->id,
            'referee_id' => $referee->id,
            'referrer_points' => 100,
            'referee_points' => 50,
            'status' => 'rewarded',
            'rewarded_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('loyalty.referrals'));

        $response->assertStatus(200);
        $response->assertSee($referrer->name);
        $response->assertSee($referee->name);
    }

    public function test_customer_has_unrewarded_referral_method(): void
    {
        $referrer = Customer::factory()->create();

        // Customer without referral
        $customer1 = Customer::factory()->create();
        $this->assertFalse($customer1->hasUnrewardedReferral());

        // Customer with referral, not rewarded
        $customer2 = Customer::factory()->create([
            'referred_by_id' => $referrer->id,
            'referral_rewarded_at' => null,
        ]);
        $this->assertTrue($customer2->hasUnrewardedReferral());

        // Customer with referral, already rewarded
        $customer3 = Customer::factory()->create([
            'referred_by_id' => $referrer->id,
            'referral_rewarded_at' => now(),
        ]);
        $this->assertFalse($customer3->hasUnrewardedReferral());
    }

    public function test_referral_code_generation_is_unique(): void
    {
        // Generate multiple codes and ensure uniqueness
        $codes = [];
        for ($i = 0; $i < 10; $i++) {
            $code = Customer::generateReferralCode();
            $this->assertNotContains($code, $codes);
            $codes[] = $code;
        }
    }

    public function test_portal_registration_with_referral_code(): void
    {
        $referrer = Customer::factory()->create();

        $response = $this->post(route('portal.register'), [
            'name' => 'New Portal User',
            'email' => 'newuser@test.com',
            'phone' => '081234567666',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'referral_code' => $referrer->referral_code,
        ]);

        $response->assertRedirect(route('portal.dashboard'));

        $newCustomer = Customer::where('email', 'newuser@test.com')->first();

        $this->assertNotNull($newCustomer);
        $this->assertEquals($referrer->id, $newCustomer->referred_by_id);
    }
}
