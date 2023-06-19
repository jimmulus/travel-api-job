<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Tour;
use App\Models\Travel;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCreateTourTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test
     */
    public function test_guest_cannot_create_a_tour(): void
    {
        $travel = Travel::factory()->create();

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v1/admin/travels/'.$travel->slug.'/tours');

        $response->assertStatus(401);
    }

    /**
     * test
     */
    public function test_logged_in_editor_cannot_create_a_tour(): void
    {
        $this->seed(RoleSeeder::class);

        $user = User::factory()->create();
        $user->roles()
            ->attach(Role::where('name', 'editor')
                ->value('id'));

        $travel = Travel::factory()->create();

        $response = $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v1/admin/travels/'.$travel->slug.'/tours');
        $response->assertStatus(403);
    }

    /**
     * test
     */
    public function test_logged_in_admin_can_create_a_travel(): void
    {
        $this->seed(RoleSeeder::class);

        $user = User::factory()->create();
        $user->roles()
            ->attach(Role::where('name', 'admin')
                ->value('id'));

        $travel = Travel::factory()->create();

        $response = $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v1/admin/travels/'.$travel->slug.'/tours', ['name' => 'Tour']);
        $response->assertStatus(422);

        $response = $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v1/admin/travels/'.$travel->slug.'/tours', [
                'name' => 'Tour',
                'starting_date' => '2023-07-21',
                'ending_date' => '2023-07-21',
                'price' => 1478.33,
            ]);
        $response->assertStatus(201);
    }

    /**
     * test
     */
    public function test_logged_in_user_can_get_list_of_tours_of_single_travel(): void
    {
        $this->seed(RoleSeeder::class);

        $user = User::factory()->create();
        $user->roles()
            ->attach(Role::where('name', 'admin')
                ->value('id'));

        $travel = Travel::factory()->create();
        Tour::factory(31)->create(['travel_id' => $travel->id]);

        $response = $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->get('/api/v1/admin/travels/'.$travel->slug.'/tours');
        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonPath('meta.last_page', 2);

    }
}
