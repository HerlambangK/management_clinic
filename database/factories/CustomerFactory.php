<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->unique()->numerify('08##########'),
            'email' => fake()->unique()->safeEmail(),
            'birthdate' => fake()->optional()->dateTimeBetween('-60 years', '-18 years'),
            'gender' => fake()->optional()->randomElement(['male', 'female']),
            'address' => fake()->optional()->address(),
            'skin_type' => fake()->optional()->randomElement(['normal', 'oily', 'dry', 'combination', 'sensitive']),
            'skin_concerns' => null,
            'allergies' => null,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
