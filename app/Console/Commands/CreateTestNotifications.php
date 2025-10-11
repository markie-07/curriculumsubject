<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\NotificationService;

class CreateTestNotifications extends Command
{
    protected $signature = 'notifications:create-test';
    protected $description = 'Create test notifications for admin and super admin users';

    public function handle()
    {
        $this->info('Creating test notifications...');

        // Get admin and super admin users
        $adminUsers = User::whereIn('role', ['admin', 'super_admin'])->get();

        if ($adminUsers->isEmpty()) {
            $this->error('No admin or super admin users found!');
            return 1;
        }

        foreach ($adminUsers as $user) {
            // Create recent notifications (within last 5 minutes)
            NotificationService::notify(
                $user->id,
                'success',
                'Welcome Back!',
                'You have successfully logged into the system.',
                ['type' => 'login_success']
            );

            NotificationService::notify(
                $user->id,
                'info',
                'System Status',
                'All systems are running normally.',
                ['type' => 'system_status']
            );

            NotificationService::notify(
                $user->id,
                'warning',
                'Pending Review',
                'You have 3 curriculum items pending review.',
                ['type' => 'pending_review', 'count' => 3]
            );

            $this->info("Created notifications for: {$user->name} ({$user->role})");
        }

        $this->info('Test notifications created successfully!');
        return 0;
    }
}
