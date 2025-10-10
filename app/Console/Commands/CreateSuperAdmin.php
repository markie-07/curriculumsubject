<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:superadmin {email} {name} {username} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new superadmin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $name = $this->argument('name');
        $username = $this->argument('username');
        $password = $this->argument('password');

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists!");
            return 1;
        }

        if (User::where('username', $username)->exists()) {
            $this->error("User with username {$username} already exists!");
            return 1;
        }

        // Create the superadmin user
        $user = User::create([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'super_admin',
            'status' => 'active',
        ]);

        $this->info("Superadmin user created successfully!");
        $this->info("Name: {$user->name}");
        $this->info("Email: {$user->email}");
        $this->info("Username: {$user->username}");
        $this->info("Role: {$user->role}");
        $this->info("Status: {$user->status}");

        return 0;
    }
}
