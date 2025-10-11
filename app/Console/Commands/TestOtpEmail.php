<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DynamicMailService;

class TestOtpEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:otp-email {email : The email address to send test OTP to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test OTP email sending functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $testOtp = '123456';

        $this->info("Testing OTP email sending to: {$email}");
        
        $result = DynamicMailService::sendOtpEmail($email, $testOtp);
        
        if ($result) {
            $this->info("✅ OTP email sent successfully!");
        } else {
            $this->error("❌ Failed to send OTP email. Check logs for details.");
        }
        
        return $result ? 0 : 1;
    }
}
