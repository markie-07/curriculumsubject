<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Super Administrator',
            'username' => 'superadmin',
            'email' => 'lhandelpamisa0@gmail.com',
            'password' => Hash::make('superadmin24'),
            'role' => 'super_admin',
        ]);

        User::create([
            'name' => 'Mark James',
            'username' => 'markjames',
            'email' => 'markjamesp11770@gmail.com', // Must match DynamicMailService config
            'password' => Hash::make('superadmin123'),
            'role' => 'super_admin',
            'status' => 'active',
        ]);

        // Admin
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'olausersms3@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Additional Super Admin Users


    }
}