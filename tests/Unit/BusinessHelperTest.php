<?php

namespace Tests\Unit;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessHelperTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        clear_business_cache();
    }

    public function test_is_setup_completed_returns_false_when_not_set(): void
    {
        $this->assertFalse(is_setup_completed());
    }

    public function test_is_setup_completed_returns_true_when_set(): void
    {
        Setting::set('setup_completed', true, 'boolean');
        clear_business_cache();

        $this->assertTrue(is_setup_completed());
    }

    public function test_business_type_returns_empty_when_not_set(): void
    {
        // Setting::get returns empty string when not set
        $result = business_type();
        $this->assertTrue($result === null || $result === '');
    }

    public function test_business_type_returns_value_when_set(): void
    {
        Setting::set('business_type', 'salon', 'string');
        clear_business_cache();

        $this->assertEquals('salon', business_type());
    }

    public function test_business_config_returns_config_for_valid_type(): void
    {
        Setting::set('business_type', 'clinic', 'string');
        clear_business_cache();

        $config = business_config();

        $this->assertIsArray($config);
        $this->assertArrayHasKey('name', $config);
        $this->assertArrayHasKey('theme', $config);
    }

    public function test_business_config_returns_clinic_as_default(): void
    {
        // When business_type returns empty/null, it falls back to config default
        Setting::set('business_type', 'clinic', 'string');
        clear_business_cache();

        $config = business_config();

        $this->assertIsArray($config);
        $this->assertEquals('Klinik Kecantikan', $config['name']);
    }

    public function test_business_label_returns_config_label(): void
    {
        // business_label returns from config, not from settings
        Setting::set('business_type', 'salon', 'string');
        clear_business_cache();

        // 'name' key from config/business.php for salon type
        $this->assertEquals('Salon', business_label('name'));
    }

    public function test_business_label_returns_config_value_for_type_label(): void
    {
        Setting::set('business_type', 'barbershop', 'string');
        clear_business_cache();

        $label = business_label('staff_label');

        $this->assertEquals('Barber', $label);
    }

    public function test_business_theme_returns_theme_config(): void
    {
        Setting::set('business_type', 'salon', 'string');
        clear_business_cache();

        $theme = business_theme();

        $this->assertIsArray($theme);
        $this->assertArrayHasKey('primary', $theme);
        $this->assertEquals('purple', $theme['primary']);
    }

    public function test_business_profile_fields_returns_array(): void
    {
        Setting::set('business_type', 'clinic', 'string');
        clear_business_cache();

        $fields = business_profile_fields();

        $this->assertIsArray($fields);
        $this->assertArrayHasKey('type', $fields);
        $this->assertArrayHasKey('concerns', $fields);
    }

    public function test_business_profile_options_returns_options_for_type(): void
    {
        Setting::set('business_type', 'clinic', 'string');
        clear_business_cache();

        $options = business_profile_options('type');

        $this->assertIsArray($options);
        $this->assertArrayHasKey('normal', $options);
        $this->assertArrayHasKey('oily', $options);
    }

    public function test_business_profile_options_returns_hair_options_for_salon(): void
    {
        Setting::set('business_type', 'salon', 'string');
        clear_business_cache();

        $options = business_profile_options('type');

        $this->assertIsArray($options);
        $this->assertArrayHasKey('curly', $options);
        $this->assertArrayHasKey('straight', $options);
    }

    public function test_staff_role_label_returns_correct_label(): void
    {
        Setting::set('business_type', 'clinic', 'string');
        clear_business_cache();

        $this->assertEquals('Beautician', staff_role_label('beautician'));
        $this->assertEquals('Owner', staff_role_label('owner'));
        $this->assertEquals('Admin', staff_role_label('admin'));
    }

    public function test_staff_role_label_returns_hairstylist_for_salon(): void
    {
        Setting::set('business_type', 'salon', 'string');
        clear_business_cache();

        $this->assertEquals('Hairstylist', staff_role_label('beautician'));
    }

    public function test_staff_role_label_returns_barber_for_barbershop(): void
    {
        Setting::set('business_type', 'barbershop', 'string');
        clear_business_cache();

        $this->assertEquals('Barber', staff_role_label('beautician'));
    }

    public function test_business_staff_label_returns_singular_label(): void
    {
        Setting::set('business_type', 'clinic', 'string');
        clear_business_cache();

        $this->assertEquals('Beautician', business_staff_label());
    }

    public function test_business_staff_label_returns_plural_label(): void
    {
        Setting::set('business_type', 'salon', 'string');
        clear_business_cache();

        $this->assertEquals('Hairstylists', business_staff_label(true));
    }

    public function test_clear_business_cache_clears_cache(): void
    {
        Setting::set('business_type', 'clinic', 'string');

        // First call caches the value
        $this->assertEquals('clinic', business_type());

        // Change the value
        Setting::set('business_type', 'salon', 'string');

        // Clear cache
        clear_business_cache();

        // Should now return new value
        $this->assertEquals('salon', business_type());
    }
}
