<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCreateTravelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test
     */
    public function test_guest_cannot_create_a_travel(): void
    {
        $response = $this->withHeader('Accept', 'application/json')->post('api/v1/admin/travels');
        $response->assertStatus(401);
    }

    /**
     * test
     */
    public function test_logged_in_editor_cannot_create_a_travel(): void
    {
        $this->seed(RoleSeeder::class);

        $user = User::factory()->create();
        $user->roles()
            ->attach(Role::where('name', 'editor')
                ->value('id'));

        $response = $this->actingAs($user)
            ->withHeader('Accept', 'application/json')
            ->post('api/v1/admin/travels');
        $response->assertStatus(403);
    }

    /**
     * test
     */
    public function test_logged_in_admin_can_create_a_travel(): void
    {
        $this->seed(RoleSeeder::class);

        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'admin')->value('id'));

        $response = $this->actingAs($user)
            ->withHeader('Accept', 'application/json')
            ->post('api/v1/admin/travels');
        $response->assertStatus(422);

        $response = $this->actingAs($user)
            ->withHeader('Accept', 'application/json')
            ->post('api/v1/admin/travels', [
                'name' => 'Travel France',
                'description' => 'Travel in style',
                'is_public' => 1,
                'number_of_days' => 14,
            ]);
        $response->assertStatus(201);
    }
}
