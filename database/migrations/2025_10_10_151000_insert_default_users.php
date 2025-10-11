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
        // Check if users table exists before inserting data
        if (Schema::hasTable('users')) {
            // Super Admin - Check if user doesn't already exist
            if (!User::where('username', 'superadmin')->exists()) {
                User::create([
                    'name' => 'Super Administrator',
                    'username' => 'superadmin',
                    'email' => 'lhandelpamisa0@gmail.com',
                    'password' => Hash::make('superadmin24'),
                    'role' => 'super_admin',
                ]);
            }

            // Admin - Check if user doesn't already exist
            if (!User::where('username', 'admin')->exists()) {
                User::create([
                    'name' => 'Administrator',
                    'username' => 'admin',
                    'email' => 'olausersms3@gmail.com',
                    'password' => Hash::make('password123'),
                    'role' => 'admin',
                ]);
            }

            // Employee User - Only has access to curriculum export tool
            if (!User::where('username', 'employee')->exists()) {
                User::create([
                    'name' => 'Employee User',
                    'username' => 'employee',
                    'email' => 'employee@sms.edu',
                    'password' => Hash::make('employee123'),
                    'role' => 'employee',
                    'status' => 'active',
                ]);
            }

            // Additional Super Admin Users
            if (!User::where('username', 'markjames')->exists()) {
                User::create([
                    'name' => 'Mark James',
                    'username' => 'markjames',
                    'email' => 'markjamesp11770@gmail.com', // Must match DynamicMailService config
                    'password' => Hash::make('superadmin123'),
                    'role' => 'super_admin',
                    'status' => 'active',
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete all users created by this migration only if users table exists
        if (Schema::hasTable('users')) {
            User::whereIn('username', [
                'superadmin',
                'admin', 
                'employee',
                'markjames'
            ])->delete();
        }
    }
};
