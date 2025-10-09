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
            'password' => Hash::make('superadmin123'),
            'role' => 'super_admin',
        ]);

        // Admin
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'olausersms3@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Regular User
        User::create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@sms.edu',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // Additional Admin User
        User::create([
            'name' => 'John Admin',
            'username' => 'johnadmin',
            'email' => 'john@sms.edu',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Additional Super Admin Users
        User::create([
            'name' => 'Secondary Super Admin',
            'username' => 'superadmin2',
            'email' => 'admin@yourdomain.com', // Must match DynamicMailService config
            'password' => Hash::make('superadmin123'),
            'role' => 'super_admin',
        ]);

        User::create([
            'name' => 'Third Super Admin',
            'username' => 'superadmin3',
            'email' => 'manager@yourdomain.com', // Must match DynamicMailService config
            'password' => Hash::make('superadmin123'),
            'role' => 'super_admin',
        ]);

        // Sample Employee Users (No app passwords needed)
        User::create([
            'name' => 'John Employee',
            'username' => 'johnemployee',
            'email' => 'john.employee@gmail.com', // Employee's personal Gmail
            'password' => Hash::make('employee123'),
            'role' => 'employee',
        ]);

        User::create([
            'name' => 'Jane Employee',
            'username' => 'janeemployee',
            'email' => 'jane.employee@gmail.com', // Employee's personal Gmail
            'password' => Hash::make('employee123'),
            'role' => 'employee',
        ]);
    }
}
