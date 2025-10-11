<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the user's profile page.
     */
    public function show()
    {
        return view('profile.show', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Validate email existence via AJAX
     */
    public function validateEmail(Request $request)
    {
        $email = $request->email;
        $userId = Auth::id();
        
        // Basic email format validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid email format'
            ]);
        }
        
        // Check if email is already taken by another user
        $emailExists = \App\Models\User::where('email', $email)
                                        ->where('id', '!=', $userId)
                                        ->exists();
        
        if ($emailExists) {
            return response()->json([
                'valid' => false,
                'message' => 'This email is already taken by another user'
            ]);
        }
        
        // Simulate email existence check (you can replace this with actual email verification)
        $isRealEmail = $this->checkEmailExists($email);
        
        return response()->json([
            'valid' => $isRealEmail,
            'message' => $isRealEmail ? 'Email is valid and reachable' : 'This email address appears to be invalid or does not exist. Please use a real, active email address.'
        ]);
    }
    
    /**
     * Check if email actually exists (enhanced validation)
     */
    private function checkEmailExists($email)
    {
        // Basic domain validation
        $domain = substr(strrchr($email, '@'), 1);
        $localPart = substr($email, 0, strpos($email, '@'));
        
        // Check if domain has MX record
        if (!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A')) {
            return false;
        }
        
        // Additional checks for common invalid domains
        $invalidDomains = ['test.com', 'example.com', 'fake.com', 'invalid.com', 'temp.com', 'tempmail.com'];
        if (in_array(strtolower($domain), $invalidDomains)) {
            return false;
        }
        
        // Enhanced validation for common email providers
        $commonProviders = [
            'gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 
            'live.com', 'msn.com', 'aol.com', 'icloud.com',
            'protonmail.com', 'zoho.com', 'mail.com'
        ];
        
        if (in_array(strtolower($domain), $commonProviders)) {
            // For common providers, do additional validation
            return $this->validateCommonEmailProvider($email, $domain, $localPart);
        }
        
        // For other domains, require stricter validation
        return $this->validateCustomDomain($domain);
    }
    
    /**
     * Validate emails from common providers (Gmail, Yahoo, etc.)
     */
    private function validateCommonEmailProvider($email, $domain, $localPart)
    {
        // Basic format checks for common providers
        $domain = strtolower($domain);
        
        // Gmail specific validation
        if ($domain === 'gmail.com') {
            // Gmail usernames must be at least 6 characters
            if (strlen($localPart) < 6) {
                return false;
            }
            
            // Gmail doesn't allow certain patterns
            if (preg_match('/^[0-9]+$/', $localPart)) { // All numbers
                return false;
            }
            
            // Check for obviously fake patterns
            $fakePatterns = [
                '/test\d*$/', '/fake\d*$/', '/dummy\d*$/', '/sample\d*$/',
                '/example\d*$/', '/temp\d*$/', '/random\d*$/', '/demo\d*$/',
                '/trial\d*$/', '/user\d*$/', '/admin\d*$/', '/null\d*$/',
                '/noreply\d*$/', '/donotreply\d*$/', '/invalid\d*$/'
            ];
            
            foreach ($fakePatterns as $pattern) {
                if (preg_match($pattern, strtolower($localPart))) {
                    return false;
                }
            }
        }
        
        // Yahoo specific validation
        if (in_array($domain, ['yahoo.com', 'yahoo.co.uk', 'yahoo.ca'])) {
            if (strlen($localPart) < 4) {
                return false;
            }
        }
        
        // Outlook/Hotmail specific validation
        if (in_array($domain, ['outlook.com', 'hotmail.com', 'live.com', 'msn.com'])) {
            if (strlen($localPart) < 5) {
                return false;
            }
        }
        
        // Additional validation: Check for sequential numbers or letters
        if (preg_match('/^[a-z]*123+[a-z]*$|^[a-z]*abc+[a-z]*$|^[a-z]*qwe+[a-z]*$/i', $localPart)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate custom domain emails
     */
    private function validateCustomDomain($domain)
    {
        // For custom domains, be more strict
        $domain = strtolower($domain);
        
        // Check if it's a known educational or business domain pattern
        if (preg_match('/\.(edu|gov|org|ac\.|edu\.)/', $domain)) {
            return true; // Educational/government domains are usually valid
        }
        
        // For other custom domains, require both MX and A records
        if (!checkdnsrr($domain, 'MX') || !checkdnsrr($domain, 'A')) {
            return false;
        }
        
        // Check if domain has a website (basic validation)
        $headers = @get_headers("http://$domain", 1);
        if (!$headers) {
            $headers = @get_headers("https://$domain", 1);
        }
        
        return $headers !== false;
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Custom validation with email existence check
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                Rule::unique('users')->ignore($user->id)
            ],
        ]);
        
        // Additional email existence validation
        $validator->after(function ($validator) use ($request) {
            if ($request->email && !$this->checkEmailExists($request->email)) {
                $validator->errors()->add('email', 'This email address appears to be invalid or does not exist. Please use a real, active email address from a valid provider like Gmail, Yahoo, or Outlook.');
            }
        });
        
        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        // Store original values for comparison
        $originalName = $user->name;
        $originalEmail = $user->email;
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Create notification for profile update with change details
        NotificationService::profileUpdatedWithDetails($user->id, $originalName, $originalEmail, $request->name, $request->email);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        // Restrict password changes for employees
        if ($user->isEmployee()) {
            return redirect()->route('profile.show')
                ->with('error', 'Employees are not allowed to change their password. Please contact your administrator.');
        }

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Create notification for password change
        NotificationService::passwordChanged(Auth::id());

        return redirect()->route('profile.show')->with('success', 'Password updated successfully!');
    }
}
