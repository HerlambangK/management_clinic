<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MemberAttendance>
 */
class MemberAttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'checked_in_at' => $this->faker->dateTimeBetween('-1 month'),
            'latitude' => $this->faker->latitude(-11, 6),
            'longitude' => $this->faker->longitude(95, 141),
            'accuracy' => $this->faker->numberBetween(5, 50),
            'status' => 'success',
        ];
    }
}
