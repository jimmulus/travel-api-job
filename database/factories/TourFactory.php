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
        $date = now()->addDays(rand(31, 150));

        return [
            'name' => fake()->name,
            'starting_date' => $date,
            'ending_date' => $date->addDays(rand(5, 21)),
            'price' => fake()->randomFloat(2, 600, 3999),
        ];
    }
}
