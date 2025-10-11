<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a fake request to test notifications
$request = Illuminate\Http\Request::create('/notifications', 'GET');

// Set up the application
$app->instance('request', $request);

// Get all users and their notifications
$users = App\Models\User::with('notifications')->get();

echo "=== DEBUG NOTIFICATIONS ===\n";
echo "Total users: " . $users->count() . "\n";
echo "Total notifications: " . App\Models\Notification::count() . "\n\n";

foreach ($users as $user) {
    echo "User: {$user->email} (ID: {$user->id}, Role: {$user->role})\n";
    echo "  Notifications count: " . $user->notifications->count() . "\n";
    
    if ($user->notifications->count() > 0) {
        echo "  Recent notifications:\n";
        foreach ($user->notifications->take(3) as $notification) {
            echo "    - {$notification->title}: {$notification->message}\n";
        }
    }
    echo "\n";
}

// Test the notification controller directly
echo "=== TESTING NOTIFICATION CONTROLLER ===\n";

try {
    // Simulate authentication for the first user
    $firstUser = App\Models\User::first();
    Auth::login($firstUser);
    
    $controller = new App\Http\Controllers\NotificationController();
    $response = $controller->index($request);
    
    echo "Controller response: " . $response->getContent() . "\n";
    
} catch (Exception $e) {
    echo "Error testing controller: " . $e->getMessage() . "\n";
}
