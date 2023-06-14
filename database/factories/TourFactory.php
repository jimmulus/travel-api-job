<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tour>
 */
class TourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = now()->addDays(rand(31, 150));
        $end = (clone $start)->addDays(rand(5, 21));

        return [
            'name' => fake()->name,
            'starting_date' => $start,
            'ending_date' => $end,
            'price' => fake()->randomFloat(2, 600, 3999),
        ];
    }
}
