<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateUserCommandTest extends TestCase
{

    use RefreshDatabase;
    /**
     * test
     */
    public function test_create_user_command_should_create_user(): void
    {
        $this->artisan('user:create')
        ->expectsOutput('Creating a user ...')
        ->expectsChoice('Choose a role for the new user', 'admin', ['admin', 'editor', 'user'], true)
        ->expectsQuestion('Fill in the user name', 'Traveler')
        ->expectsQuestion('Fill in the user email', 'traveler@travel.com' )
        ->expectsQuestion('Fill in a user password with a minimum of 8 characters', 'password')
        ->expectsQuestion('Repeate the password', 'password')
        ->expectsOutput('User account created.')
        ->assertExitCode(0)
        ->assertSuccessful();
    }

    /**
     * test
     */
    public function test_create_user_command_should_return_error_message(): void
    {
        User::factory()->create(['email' => 'traveler@travel.com']);

        $this->artisan('user:create')
        ->expectsOutput('Creating a user ...')
        ->expectsChoice('Choose a role for the new user', 'admin', ['admin', 'editor', 'user'], true)
        ->expectsQuestion('Fill in the user name', 'Traveler')
        ->expectsQuestion('Fill in the user email', 'traveler@travel.com' )
        ->expectsQuestion('Fill in a user password with a minimum of 8 characters', 'password')
        ->expectsQuestion('Repeate the password', 'password')
        ->expectsOutput('User not created. See error messages below:')
        ->expectsOutputToContain('The email has already been taken.')
        ->assertExitCode(422)
        ->assertFailed();
    }
}
