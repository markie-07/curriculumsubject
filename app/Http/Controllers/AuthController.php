<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Otp;
use App\Models\LoginAttempt;
use App\Services\DynamicMailService;
use App\Services\ActivityLogService;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $email = $request->email;
        $ipAddress = $request->ip();

        // Check for existing login attempts
        $loginAttempt = LoginAttempt::findOrCreateAttempt($email, $ipAddress);

        // Check if user is currently locked out
        if ($loginAttempt->isLockedOut()) {
            $remainingTime = $loginAttempt->getRemainingLockoutTime();
            $minutes = floor($remainingTime / 60);
            $seconds = $remainingTime % 60;
            
            $lockoutMessage = "Too many failed attempts. Account locked for {$minutes}m {$seconds}s.";
            
            // Add progressive lockout information
            if ($loginAttempt->lockout_count > 1) {
                $lockoutMessage .= " (Extended lockout due to repeated failures)";
            }
            
            return back()->withErrors([
                'email' => $lockoutMessage,
            ])->onlyInput('email');
        }

        $credentials = [
            'email' => $email,
            'password' => $request->password,
        ];

        // Check if credentials are valid
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Reset login attempts on successful login
            $loginAttempt->resetAttempts();
            
            // Generate and send OTP to the user's email
            $otp = Otp::generateOtp($user->email);
            
            // Send OTP email using dynamic mail service
            DynamicMailService::sendOtpEmail($user->email, $otp->otp_code);
            
            // Store user ID and email in session for OTP verification
            $request->session()->put('pending_user_id', $user->id);
            $request->session()->put('pending_user_email', $user->email);
            
            // Logout user temporarily until OTP is verified
            Auth::logout();
            
            // Regenerate session to prevent CSRF token issues
            $request->session()->regenerate();
            
            return redirect()->route('otp.verify')->with('success', 'OTP has been sent to your email (' . $user->email . '). Please check your inbox.');
        }

        // Increment failed attempts
        $loginAttempt->incrementAttempts();
        
        // Refresh the model to get updated values after incrementAttempts
        $loginAttempt->refresh();
        
        $errorMessage = 'The provided credentials do not match our records.';
        
        if ($loginAttempt->isLockedOut()) {
            $lockoutDuration = $loginAttempt->lockout_count == 1 ? '1 minute' : '5 minutes';
            $errorMessage = "Too many failed attempts. Account locked for {$lockoutDuration}.";
            
            if ($loginAttempt->lockout_count > 1) {
                $errorMessage .= " (Extended lockout due to repeated failures)";
            }
        } else {
            // Determine warning message based on lockout history
            if ($loginAttempt->lockout_count > 0 && 
                $loginAttempt->first_lockout_at && 
                $loginAttempt->first_lockout_at->diffInHours(now()) <= 1) {
                // User has been locked out before within the last hour
                $errorMessage .= " ⚠️ Warning: Next failed attempt will result in 5-minute lockout.";
            } else {
                // Standard warning for first-time users or after reset
                $remainingAttempts = 5 - $loginAttempt->attempts;
                if ($loginAttempt->attempts >= 3) {
                    $errorMessage .= " Warning: {$remainingAttempts} attempts remaining before 1-minute lockout.";
                }
            }
        }

        return back()->withErrors([
            'email' => $errorMessage,
        ])->onlyInput('email');
    }

    /**
     * Show OTP verification form
     */
    public function showOtpForm()
    {
        if (!session('pending_user_id')) {
            return redirect()->route('login')->withErrors(['error' => 'Please login first.']);
        }
        
        return view('auth.otp-verify');
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6',
        ]);

        $userId = session('pending_user_id');
        $userEmail = session('pending_user_email');
        
        if (!$userId || !$userEmail) {
            return redirect()->route('login')->withErrors(['error' => 'Session expired. Please login again.']);
        }

        // Verify OTP
        if (Otp::verifyOtp($userEmail, $request->otp_code)) {
            // OTP is valid, log in the user
            $user = User::find($userId);
            Auth::login($user);
            
            // Clear session
            $request->session()->forget(['pending_user_id', 'pending_user_email']);
            $request->session()->regenerate();
            
            // Log successful login
            \Log::info('User logged in successfully with OTP', [
                'email' => $user->email,
                'role' => $user->role,
                'ip' => $request->ip(),
                'redirect_intended' => session()->get('url.intended', 'none'),
                'is_employee' => $user->isEmployee()
            ]);
            
            // Log activity for employees
            if ($user->isEmployee()) {
                ActivityLogService::logLogin($user);
                $user->updateLastActivity();
                // Redirect employees directly to curriculum export tool
                return redirect()->route('curriculum_export_tool');
            }
            
            // For admin and super_admin users, redirect to dashboard
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'otp_code' => 'Invalid or expired OTP code.',
        ]);
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $userId = session('pending_user_id');
        $userEmail = session('pending_user_email');
        
        if (!$userId || !$userEmail) {
            return redirect()->route('login')->withErrors(['error' => 'Session expired. Please login again.']);
        }

        // Generate new OTP
        $otp = Otp::generateOtp($userEmail);
        
        // Send OTP email using dynamic mail service
        DynamicMailService::sendOtpEmail($userEmail, $otp->otp_code);
        
        return back()->with('success', 'New OTP has been sent to your email (' . $userEmail . ').');
    }


    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Log activity for employees before logout
            if ($user && $user->isEmployee()) {
                ActivityLogService::logLogout($user);
            }
            
            Auth::logout();
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Add cache control headers to prevent back button access
            $response = redirect()->route('login')->with('logout_success', 'You have been successfully logged out.');
            $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
            
            return $response;
        } catch (\Exception $e) {
            // If there's any error (including CSRF token issues), force logout anyway
            \Log::warning('Logout error occurred: ' . $e->getMessage());
            
            // Force logout without session validation
            Auth::logout();
            
            // Clear all session data
            $request->session()->flush();
            $request->session()->regenerate();
            
            return redirect()->route('login')->with('logout_success', 'You have been successfully logged out.');
        }
    }
}
