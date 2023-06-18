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
        return [
            'name' => fake()->word(),
            'category_id' => fake()->numberBetween(1,50),
            'price' => fake()->randomFloat(2,10,500),
            'exists' => fake()->boolean(),
            'count' => fake()->numberBetween(1,400),
        ];
    }
}
