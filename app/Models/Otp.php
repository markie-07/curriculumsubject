<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Otp extends Model
{
    protected $fillable = [
        'email',
        'otp_code',
        'expires_at',
        'is_used'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean'
    ];

    /**
     * Generate a new OTP for the given email
     */
    public static function generateOtp($email)
    {
        // Delete any existing unused OTPs for this email
        self::where('email', $email)->where('is_used', false)->delete();
        
        // Generate 6-digit OTP
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Create new OTP record
        return self::create([
            'email' => $email,
            'otp_code' => $otpCode,
            'expires_at' => Carbon::now()->addMinutes(10), // OTP expires in 10 minutes
            'is_used' => false
        ]);
    }

    /**
     * Verify OTP code
     */
    public static function verifyOtp($email, $otpCode)
    {
        $otp = self::where('email', $email)
                   ->where('otp_code', $otpCode)
                   ->where('is_used', false)
                   ->where('expires_at', '>', Carbon::now())
                   ->first();

        if ($otp) {
            $otp->update(['is_used' => true]);
            return true;
        }

        return false;
    }

    /**
     * Check if OTP is expired
     */
    public function isExpired()
    {
        return Carbon::now()->greaterThan($this->expires_at);
    }
}
