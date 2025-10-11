<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class DynamicMailService
{
    /**
     * Email configurations for different users
     */
    private static $emailConfigs = [
        'lhandelpamisa0@gmail.com' => [
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'username' => 'lhandelpamisa0@gmail.com',
            'password' => 'qqqzccrjpqmbpztz', // Your current app password
            'encryption' => 'tls',
            'from_name' => 'Student Management System'
        ],
        'olausersms3@gmail.com' => [
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'username' => 'olausersms3@gmail.com',
            'password' => 'majlyukqyvpblqmb', // App password for admin user
            'encryption' => 'tls',
            'from_name' => 'Student Management System'
        ],
        'markjamesp11770@gmail.com' => [
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'username' => 'markjamesp11770@gmail.com',
            'password' => 'eyafpyveggzylpge', // App password for Mark James superadmin (spaces removed)
            'encryption' => 'tls',
            'from_name' => 'Student Management System'
        ],
    ];

    /**
     * Configure mail settings for a specific email
     */
    public static function configureMailForEmail($email)
    {
        if (!isset(self::$emailConfigs[$email])) {
            // Fallback to default configuration
            return false;
        }

        $config = self::$emailConfigs[$email];

        // Set the mail configuration dynamically
        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', $config['host']);
        Config::set('mail.mailers.smtp.port', $config['port']);
        Config::set('mail.mailers.smtp.username', $config['username']);
        Config::set('mail.mailers.smtp.password', $config['password']);
        Config::set('mail.mailers.smtp.encryption', $config['encryption']);
        Config::set('mail.from.address', $config['username']);
        Config::set('mail.from.name', $config['from_name']);

        // Purge the mail manager to force reconfiguration
        app()->forgetInstance('mail.manager');
        app()->forgetInstance('mailer');

        return true;
    }

    /**
     * Send OTP email using centralized email system
     */
    public static function sendOtpEmail($userEmail, $otpCode)
    {
        // Use centralized email configuration for employees
        $senderEmail = self::getSystemSenderEmail($userEmail);
        
        // Configure mail for the sender (not the recipient)
        if (!self::configureMailForEmail($senderEmail)) {
            \Log::warning("No mail configuration found for sender {$senderEmail}, using default");
        }

        try {
            \Log::info("Attempting to send OTP to {$userEmail} using sender {$senderEmail}");
            
            Mail::raw("Your OTP code is: {$otpCode}\n\nThis code will expire in 10 minutes.\n\nIf you didn't request this code, please ignore this email.\n\n---\nStudent Management System", function ($message) use ($userEmail) {
                $message->to($userEmail)
                        ->subject('Your OTP Code - Student Management System');
            });

            \Log::info("OTP sent successfully to {$userEmail}");
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to send OTP email to {$userEmail}: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Determine which system email to use for sending
     */
    private static function getSystemSenderEmail($recipientEmail)
    {
        // Check if recipient has their own email config (Super Admin/Admin)
        if (isset(self::$emailConfigs[$recipientEmail])) {
            return $recipientEmail; // Use their own email
        }
        
        // For employees, use admin email as sender
        return 'olausersms3@gmail.com'; // Default to admin email for employees
    }
}
