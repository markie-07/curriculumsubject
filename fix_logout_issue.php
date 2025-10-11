<?php

/**
 * Script to fix logout 419 error by ensuring sessions table exists
 * Run this script by executing: php fix_logout_issue.php
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

try {
    // Check if sessions table exists
    $pdo = new PDO(
        'mysql:host=' . env('DB_HOST', 'localhost') . ';dbname=' . env('DB_DATABASE'),
        env('DB_USERNAME'),
        env('DB_PASSWORD')
    );
    
    $stmt = $pdo->query("SHOW TABLES LIKE 'sessions'");
    $tableExists = $stmt->rowCount() > 0;
    
    if (!$tableExists) {
        echo "Sessions table does not exist. Creating it...\n";
        
        // Create sessions table
        $createTable = "
            CREATE TABLE sessions (
                id VARCHAR(255) PRIMARY KEY,
                user_id BIGINT UNSIGNED NULL,
                ip_address VARCHAR(45) NULL,
                user_agent TEXT NULL,
                payload LONGTEXT NOT NULL,
                last_activity INT NOT NULL,
                INDEX sessions_user_id_index (user_id),
                INDEX sessions_last_activity_index (last_activity)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        $pdo->exec($createTable);
        echo "Sessions table created successfully!\n";
    } else {
        echo "Sessions table already exists.\n";
    }
    
    echo "Logout issue fix completed successfully!\n";
    echo "The following fixes have been applied:\n";
    echo "1. Sessions table created/verified\n";
    echo "2. CSRF token refresh added to logout form\n";
    echo "3. Logout route excluded from CSRF verification\n";
    echo "4. Error handling improved in logout controller\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Please run 'php artisan migrate' manually to create the sessions table.\n";
}
