<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = fake()->time('H:00');
        $durationMinutes = fake()->randomElement([30, 45, 60, 90]);
        $endTime = \Carbon\Carbon::parse($startTime)->addMinutes($durationMinutes)->format('H:i:s');

        return [
            'customer_id' => Customer::factory(),
            'service_id' => Service::factory(),
            'staff_id' => null,
            'appointment_date' => fake()->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => fake()->randomElement(['pending', 'confirmed', 'completed']),
            'source' => fake()->randomElement(['walk_in', 'phone', 'online']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
