<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelToursListTest extends TestCase
{
    use RefreshDatabase;

    protected array $publicTour = ['is_public' => true, 'name' => 'Public'];
    protected array $notPublicTour = ['is_public' => false,  'name' => 'Not Public'];

    /**
     * test
     */
    public function test_tours_list_returns_paginated_data_correctly(): void
    {
        $travel = Travel::factory()->create($this->publicTour);
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
        $travel = Travel::factory()->create($this->publicTour);
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
        $travel = Travel::factory()->create($this->publicTour);
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
    public function test_tours_list_should_return_404_not_found_if_not_public(): void
    {
        $travel = Travel::factory()->create($this->notPublicTour);
        Tour::factory(5)->create(['travel_id' => $travel->id,]);

        $response = $this->get('api/v1/travels/'.$travel->slug.'/tours');

        $response->assertStatus(404);
    }

    /**
     * test
     */
    public function test_tours_list_filters_prices_correctly(): void
    {
        $travel = Travel::factory()->create($this->publicTour);
        $cheap = Tour::factory()->create([
                'travel_id' => $travel->id,
                'price' => 499.47]);
        $expensive = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 3499.47]);
        $endpoint = 'api/v1/travels/'.$travel->slug.'/tours';

        $response = $this->get($endpoint . '?priceFrom=100');
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $cheap->id]);
        $response->assertJsonFragment(['id' => $expensive->id]);

        $response = $this->get($endpoint . '?priceFrom=1000');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonMissing(['id' => $cheap->id]);
        $response->assertJsonFragment(['id' => $expensive->id]);

        $response = $this->get($endpoint . '?priceFrom=4500');
        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
        $response->assertJsonMissing(['id' => $cheap->id]);
        $response->assertJsonMissing(['id' => $expensive->id]);

        $response = $this->get($endpoint . '?priceTo=4500');
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $cheap->id]);
        $response->assertJsonFragment(['id' => $expensive->id]);

        $response = $this->get($endpoint . '?priceTo=1000');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['id' => $cheap->id]);
        $response->assertJsonMissing(['id' => $expensive->id]);

        $response = $this->get($endpoint . '?priceTo=450');
        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
        $response->assertJsonMissing(['id' => $cheap->id]);
        $response->assertJsonMissing(['id' => $expensive->id]);

        $response = $this->get($endpoint . '?priceFrom=1000&priceTo=4500');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonMissing(['id' => $cheap->id]);
        $response->assertJsonFragment(['id' => $expensive->id]);
    }

    /**
     * test
     */
    public function test_tours_list_filters_dates_correctly(): void
    {
        $travel = Travel::factory()->create($this->publicTour);
        $early = Tour::factory()->create([
            'travel_id' => $travel->id,
            'starting_date' => '2023-07-01',
            'ending_date' => '2023-07-01'
        ]);
        $late = Tour::factory()->create([
            'travel_id' => $travel->id,
            'starting_date' => '2023-10-21',
            'ending_date' => '2023-10-21',
        ]);
        $endpoint = 'api/v1/travels/'.$travel->slug.'/tours';

        $response = $this->get($endpoint . '?dateFrom=2023-06-30');
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $early->id]);
        $response->assertJsonFragment(['id' => $late->id]);

        $response = $this->get($endpoint . '?dateFrom=2023-08-01');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonMissing(['id' => $early->id]);
        $response->assertJsonFragment(['id' => $late->id]);

        $response = $this->get($endpoint . '?dateFrom=2023-12-31');
        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
        $response->assertJsonMissing(['id' => $early->id]);
        $response->assertJsonMissing(['id' => $late->id]);

        $response = $this->get($endpoint . '?dateTo=2023-12-31');
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $early->id]);
        $response->assertJsonFragment(['id' => $late->id]);

        $response = $this->get($endpoint . '?dateTo=2023-08-01');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['id' => $early->id]);
        $response->assertJsonMissing(['id' => $late->id]);

        $response = $this->get($endpoint . '?dateTo=2023-06-30');
        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
        $response->assertJsonMissing(['id' => $early->id]);
        $response->assertJsonMissing(['id' => $late->id]);

        $response = $this->get($endpoint . '?dateFrom=2023-08-01&dateTo=2023-12-31');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonMissing(['id' => $early->id]);
        $response->assertJsonFragment(['id' => $late->id]);
    }

    /**
     * test
     */
    public function test_tours_list_returns_validation_errors(): void
    {
        $travel = Travel::factory()->create($this->publicTour);
        Tour::factory()
            ->create([
                'travel_id' => $travel->id,
                'price' => 499.47
            ]);

        $endpoint = 'api/v1/travels/'.$travel->slug.'/tours';

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($endpoint . '?priceFrom=notNumeric');
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('priceFrom');

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($endpoint . '?sortBy=unsortable');
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sortBy');
    }
}
