<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = ['Madrid', 'Barcelona', 'Valencia', 'Sevilla', 'Zaragoza', 'Málaga', 'Bilbao'];

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->streetAddress(),
            'city' => fake()->randomElement($cities),
            'postal_code' => fake()->postcode(),
            'country' => 'España',
        ];
    }

    /**
     * Cliente sin dirección
     */
    public function withoutAddress(): static
    {
        return $this->state(fn (array $attributes) => [
            'address' => null,
            'city' => null,
            'postal_code' => null,
        ]);
    }

    /**
     * Cliente de Madrid
     */
    public function fromMadrid(): static
    {
        return $this->state(fn (array $attributes) => [
            'city' => 'Madrid',
            'postal_code' => '28001',
        ]);
    }

    /**
     * Cliente internacional
     */
    public function international(): static
    {
        return $this->state(fn (array $attributes) => [
            'country' => fake()->randomElement(['Francia', 'Italia', 'Portugal', 'Alemania']),
        ]);
    }
}
