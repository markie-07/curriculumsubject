<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Update last activity for authenticated users
        if (Auth::check()) {
            $user = Auth::user();
            
            // Update activity on every request for real-time tracking (5 second window)
            try {
                // Force update the last_activity directly
                $user->last_activity = now();
                $user->save();
            } catch (\Exception $e) {
                // Log error but don't break the request
                \Log::warning('Failed to update user activity: ' . $e->getMessage());
            }
        }

        return $response;
    }
}
