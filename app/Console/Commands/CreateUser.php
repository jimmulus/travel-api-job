<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to generate a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating a user ...');
        $type = $this->choice(
            'Choose a role for the new user',
            ['admin', 'editor', 'user'],
            2
        );
        $role = Role::where('name', $type)->first();

        $name = $this->ask('Fill in the user name');
        $email = $this->ask('Fill in the user email');
        $password = $this->secret('Fill in a user password with a minimum of 8 characters');
        $password_confirmation = $this->secret('Repeate the password');
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password_confirmation,
        ], [
            'name' => ['string', 'max:255'],
            'email' => ['email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        if ($validator->fails()) {
            $this->info('User not created. See error messages below:');

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return 422;
        }
        $validated = $validator->validated();

        DB::transaction(function () use ($validated, $role) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->roles()->attach($role);
        });

        $this->info('User account created.');

        return 0;
    }
}
