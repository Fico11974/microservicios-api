<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $stock = fake()->numberBetween(0, 100);
        $minStock = fake()->numberBetween(5, 15);
        $maxStock = fake()->numberBetween(50, 200);

        // Determinar estado basado en stock
        $status = 'available';
        if ($stock <= 0) {
            $status = 'out_of_stock';
        } elseif ($stock <= $minStock) {
            $status = 'available'; // Stock bajo pero disponible
        }

        return [
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(10),
            'price' => fake()->randomFloat(2, 5, 500),
            'stock' => $stock,
            'min_stock' => $minStock,
            'max_stock' => $maxStock,
            'status' => $status,
        ];
    }

    /**
     * Producto con stock bajo
     */
    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => fake()->numberBetween(1, 5),
            'min_stock' => 10,
            'status' => 'available',
        ]);
    }

    /**
     * Producto sin stock
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
            'status' => 'out_of_stock',
        ]);
    }

    /**
     * Producto descontinuado
     */
    public function discontinued(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'discontinued',
            'stock' => 0,
        ]);
    }

    /**
     * Producto más vendido (stock reducido)
     */
    public function bestSeller(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => fake()->numberBetween(5, 20),
            'max_stock' => 100,
            'status' => 'available',
        ]);
    }

    /**
     * Producto económico
     */
    public function cheap(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => fake()->randomFloat(2, 5, 50),
        ]);
    }

    /**
     * Producto premium
     */
    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => fake()->randomFloat(2, 200, 1000),
        ]);
    }
}
