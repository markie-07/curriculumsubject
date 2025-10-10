<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
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

        // Employee User - Only has access to curriculum export tool
        User::create([
            'name' => 'Employee User',
            'username' => 'employee',
            'email' => 'employee@sms.edu',
            'password' => Hash::make('employee123'),
            'role' => 'employee',
            'status' => 'active',
        ]);

        // Additional Super Admin Users
        User::create([
            'name' => 'Mark James',
            'username' => 'markjames',
            'email' => 'markjamesp11770@gmail.com', // Must match DynamicMailService config
            'password' => Hash::make('superadmin123'),
            'role' => 'super_admin',
            'status' => 'active',
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete all users created by this migration
        User::whereIn('username', [
            'superadmin',
            'admin', 
            'employee',
            'markjames'
        ])->delete();
    }
};
