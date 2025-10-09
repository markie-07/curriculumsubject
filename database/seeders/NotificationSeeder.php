<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->count() > 0) {
            // Create sample notifications for testing
            foreach ($users as $user) {
                // Welcome notification
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'info',
                    'title' => 'Welcome to SMS',
                    'message' => 'Welcome to the Student Management System! Your account has been set up successfully.',
                    'data' => json_encode(['type' => 'welcome']),
                    'created_at' => now()->subDays(2),
                ]);

                // System update notification
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'success',
                    'title' => 'System Updated',
                    'message' => 'The system has been updated with new features and improvements.',
                    'data' => json_encode(['type' => 'system_update']),
                    'created_at' => now()->subHours(6),
                ]);

                // Recent activity notification (unread)
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'info',
                    'title' => 'Profile Reminder',
                    'message' => 'Please review and update your profile information if needed.',
                    'data' => json_encode(['type' => 'profile_reminder']),
                    'created_at' => now()->subHours(2),
                ]);
            }

            // Create admin-specific notifications
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'warning',
                    'title' => 'Curriculum Review Required',
                    'message' => 'Several curriculums require compliance review before the next semester.',
                    'data' => json_encode(['type' => 'curriculum_review']),
                    'created_at' => now()->subMinutes(30),
                ]);

                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'success',
                    'title' => 'Export Completed',
                    'message' => 'Your curriculum export has been completed successfully.',
                    'data' => json_encode(['type' => 'export_completed', 'file_name' => 'curriculum_export_2025.pdf']),
                    'created_at' => now()->subMinutes(15),
                ]);
            }
        }
    }
}
