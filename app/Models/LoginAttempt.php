<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LoginAttempt extends Model
{
    protected $fillable = [
        'email',
        'ip_address',
        'attempts',
        'lockout_count',
        'last_attempt_at',
        'locked_until',
        'first_lockout_at'
    ];

    protected $casts = [
        'last_attempt_at' => 'datetime',
        'locked_until' => 'datetime',
        'first_lockout_at' => 'datetime',
    ];

    /**
     * Check if the user is currently locked out
     */
    public function isLockedOut(): bool
    {
        $isLocked = $this->locked_until && $this->locked_until->isFuture();
        
        // If lockout has expired, reset attempts to give fresh start
        if (!$isLocked && $this->locked_until && $this->attempts > 0) {
            $this->update(['attempts' => 0]);
        }
        
        // If more than 1 hour has passed since first lockout, reset progressive tracking
        if ($this->first_lockout_at && $this->first_lockout_at->diffInHours(now()) > 1) {
            $this->update([
                'lockout_count' => 0,
                'first_lockout_at' => null
            ]);
        }
        
        return $isLocked;
    }

    /**
     * Get remaining lockout time in seconds
     */
    public function getRemainingLockoutTime(): int
    {
        if (!$this->isLockedOut()) {
            return 0;
        }
        
        return $this->locked_until->diffInSeconds(now());
    }

    /**
     * Increment failed attempts with progressive lockout
     */
    public function incrementAttempts(): void
    {
        $this->increment('attempts');
        $this->update(['last_attempt_at' => now()]);
        
        // Determine lockout threshold based on lockout history
        $lockoutThreshold = $this->getLockoutThreshold();
        
        // Lock account when threshold is reached
        if ($this->attempts >= $lockoutThreshold) {
            $this->applyLockout();
        }
    }

    /**
     * Get the number of attempts needed to trigger lockout
     */
    private function getLockoutThreshold(): int
    {
        // If user has never been locked out, require 5 attempts
        if ($this->lockout_count == 0) {
            return 5;
        }
        
        // If user has been locked out before and it's within 1 hour, only 1 attempt needed
        if ($this->first_lockout_at && $this->first_lockout_at->diffInHours(now()) <= 1) {
            return 1;
        }
        
        // If more than 1 hour has passed since first lockout, reset to 5 attempts
        return 5;
    }

    /**
     * Apply lockout with progressive timing
     */
    private function applyLockout(): void
    {
        $this->increment('lockout_count');
        
        // Set first lockout timestamp if this is the first lockout
        if ($this->lockout_count == 1) {
            $this->update(['first_lockout_at' => now()]);
        }
        
        // Determine lockout duration based on lockout count and timing
        $lockoutDuration = $this->calculateLockoutDuration();
        
        $this->update([
            'locked_until' => now()->addMinutes($lockoutDuration),
            'attempts' => 0 // Reset attempts after lockout - user gets fresh 5 attempts after lockout expires
        ]);
    }

    /**
     * Calculate progressive lockout duration
     */
    private function calculateLockoutDuration(): int
    {
        // If this is the first lockout, always 1 minute
        if ($this->lockout_count == 1) {
            return 1;
        }
        
        // If first lockout was more than 1 hour ago, this should have been reset already
        // But as a safety check, reset to 1 minute
        if ($this->first_lockout_at && $this->first_lockout_at->diffInHours(now()) > 1) {
            $this->update([
                'lockout_count' => 1,
                'first_lockout_at' => now()
            ]);
            return 1;
        }
        
        // Progressive lockout: 1 minute for first, 5 minutes for subsequent
        return 5;
    }

    /**
     * Get lockout duration in minutes for display
     */
    public function getLockoutDurationMinutes(): int
    {
        if (!$this->isLockedOut()) {
            return 0;
        }
        
        return $this->locked_until->diffInMinutes(now());
    }

    /**
     * Reset attempts after successful login
     */
    public function resetAttempts(): void
    {
        $this->update([
            'attempts' => 0,
            'locked_until' => null,
            'last_attempt_at' => now()
        ]);
        
        // Keep lockout_count and first_lockout_at for progressive tracking
        // They will be reset after 1 hour of no lockouts
    }

    /**
     * Find or create login attempt record
     */
    public static function findOrCreateAttempt(string $email, string $ipAddress): self
    {
        return static::firstOrCreate(
            [
                'email' => $email,
                'ip_address' => $ipAddress
            ],
            [
                'attempts' => 0,
                'lockout_count' => 0,
                'last_attempt_at' => now()
            ]
        );
    }

    /**
     * Clean up old attempt records (older than 24 hours)
     */
    public static function cleanupOldAttempts(): void
    {
        static::where('last_attempt_at', '<', now()->subDay())->delete();
    }
}
