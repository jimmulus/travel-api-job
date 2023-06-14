<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelToursListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test
     */
    public function test_tours_list_returns_paginated_data_correctly(): void
    {
        $travel = Travel::factory()->create(['is_public' => true]);
        Tour::factory(16)->create(['travel_id' => $travel->id]);
        print($travel->slug);
        $response = $this->get('api/v1/travels/'.$travel->slug.'/tours');

        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonPath('meta.last_page', 2);
    }

    /**
     * test
     */
    public function test_tours_list_returns_ordered_bystarting_date(): void
    {
        $travel = Travel::factory()->create(['is_public' => true]);
        $tour1 = Tour::factory()->create([
                'travel_id' => $travel->id,
                'starting_date' => \Carbon\Carbon::make('2023-07-01')]);
        Tour::factory()->create([
            'travel_id' => $travel->id,
            'starting_date' => \Carbon\Carbon::make('2023-09-27')]);

        $response = $this->get('api/v1/travels/'.$travel->slug.'/tours');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonPath('data.0.starting_date', '2023-07-01');
    }

    /**
     * test
     */
    public function test_tours_list_returns_correct_price_format(): void
    {
        $travel = Travel::factory()->create(['is_public' => true]);
        Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 1299.99
        ]);

        $response = $this->get('api/v1/travels/'.$travel->slug.'/tours');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['price'=> strval(number_format(1299.99, 2))]);
    }

    /**
     * test
     */
    public function test_tours_should_return_404_not_found_if_not_public(): void
    {
        $travel = Travel::factory()->create(['is_public' => false]);
        Tour::factory(5)->create(['travel_id' => $travel->id,]);

        $response = $this->get('api/v1/travels/'.$travel->slug.'/tours');

        $response->assertStatus(404);
    }
}
