<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test
     */
    public function test_login_returns_access_token_after_login(): void
    {
        $user = User::factory()->create();

        $response = $this->post('api/v1/auth/login', ['email' => $user->email, 'password' => 'password']);
        $response->assertStatus(200);
        $response->assertJsonStructure(['access_token']);
    }

    /**
     * test
     */
    public function test_login_returns_error_with_wrong_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->post('api/v1/auth/login', ['email' => 'incorrect@email.com', 'password' => 'password']);
        $response->assertStatus(422);
    }
}
