<?php

namespace Database\Seeders;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Database\Seeder;

class DummyTravelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Travel::factory()
            ->has(Tour::factory(rand(2, 20)))
            ->create([
                'name' => 'Travel 1',
                'is_public' => true,
            ]);
        Travel::factory()
            ->has(Tour::factory(rand(2, 20)))
            ->create([
                'name' => 'Travel 1',
                'is_public' => false,
            ]);
    }
}
