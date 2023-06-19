<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->hasAttached(Role::where('name', 'admin')->first())
            ->create(['email' => 'admin@travel.com']);
        User::factory()
            ->hasAttached(Role::where('name', 'editor')->first())
            ->create(['email' => 'editor@travel.com']);
        User::factory()
            ->hasAttached(Role::where('name', 'user')->first())
            ->create(['email' => 'user@travel.com']);
    }
}
