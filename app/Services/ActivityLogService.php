<?php

namespace App\Services;

use App\Models\User;
use App\Models\EmployeeActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    /**
     * Log user activity
     */
    public static function log(string $type, string $description, array $metadata = [], ?User $user = null): EmployeeActivityLog
    {
        $user = $user ?? Auth::user();
        
        if (!$user) {
            throw new \Exception('No user provided for activity logging');
        }

        return $user->logActivity($type, $description, $metadata);
    }

    /**
     * Log export activity
     */
    public static function logExport(string $exportType, string $fileName = null, array $additionalData = []): EmployeeActivityLog
    {
        $metadata = array_merge([
            'export_type' => $exportType,
            'file_name' => $fileName,
            'timestamp' => now()->toISOString(),
        ], $additionalData);

        return self::log(
            'export',
            "Exported {$exportType}" . ($fileName ? " as {$fileName}" : ''),
            $metadata
        );
    }

    /**
     * Log login activity
     */
    public static function logLogin(?User $user = null): EmployeeActivityLog
    {
        return self::log(
            'login',
            'User logged in',
            [
                'login_time' => now()->toISOString(),
                'browser' => self::getBrowserInfo(),
            ],
            $user
        );
    }

    /**
     * Log logout activity
     */
    public static function logLogout(?User $user = null): EmployeeActivityLog
    {
        return self::log(
            'logout',
            'User logged out',
            [
                'logout_time' => now()->toISOString(),
            ],
            $user
        );
    }

    /**
     * Log page view activity
     */
    public static function logPageView(string $page, array $additionalData = []): EmployeeActivityLog
    {
        $metadata = array_merge([
            'page' => $page,
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ], $additionalData);

        return self::log(
            'view',
            "Viewed {$page}",
            $metadata
        );
    }

    /**
     * Get recent activities for a user
     */
    public static function getRecentActivities(User $user, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return $user->activityLogs()
                   ->orderBy('created_at', 'desc')
                   ->limit($limit)
                   ->get();
    }

    /**
     * Get activities by type for a user
     */
    public static function getActivitiesByType(User $user, string $type, int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        return $user->activityLogs()
                   ->ofType($type)
                   ->orderBy('created_at', 'desc')
                   ->limit($limit)
                   ->get();
    }

    /**
     * Get activities for date range
     */
    public static function getActivitiesForDateRange(User $user, string $startDate, string $endDate): \Illuminate\Database\Eloquent\Collection
    {
        return $user->activityLogs()
                   ->dateRange($startDate, $endDate)
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * Get all employee activities (for admin view)
     */
    public static function getAllEmployeeActivities(int $limit = 100): \Illuminate\Database\Eloquent\Collection
    {
        return EmployeeActivityLog::with('user')
                                 ->whereHas('user', function($query) {
                                     $query->where('role', 'employee');
                                 })
                                 ->orderBy('created_at', 'desc')
                                 ->limit($limit)
                                 ->get();
    }

    /**
     * Get activity statistics
     */
    public static function getActivityStats(User $user = null): array
    {
        $query = EmployeeActivityLog::query();
        
        if ($user) {
            $query->where('user_id', $user->id);
        } else {
            // Only employee activities for admin view
            $query->whereHas('user', function($q) {
                $q->where('role', 'employee');
            });
        }

        $totalActivities = $query->count();
        $todayActivities = $query->whereDate('created_at', today())->count();
        $weekActivities = $query->where('created_at', '>=', now()->subWeek())->count();
        $monthActivities = $query->where('created_at', '>=', now()->subMonth())->count();

        $activityTypes = $query->select('activity_type')
                              ->selectRaw('count(*) as count')
                              ->groupBy('activity_type')
                              ->pluck('count', 'activity_type')
                              ->toArray();

        return [
            'total' => $totalActivities,
            'today' => $todayActivities,
            'week' => $weekActivities,
            'month' => $monthActivities,
            'by_type' => $activityTypes,
        ];
    }

    /**
     * Get browser information
     */
    private static function getBrowserInfo(): array
    {
        $userAgent = request()->userAgent();
        
        return [
            'user_agent' => $userAgent,
            'platform' => self::getPlatform($userAgent),
            'browser' => self::getBrowser($userAgent),
        ];
    }

    /**
     * Extract platform from user agent
     */
    private static function getPlatform(string $userAgent): string
    {
        if (preg_match('/Windows/i', $userAgent)) return 'Windows';
        if (preg_match('/Mac/i', $userAgent)) return 'Mac';
        if (preg_match('/Linux/i', $userAgent)) return 'Linux';
        if (preg_match('/Android/i', $userAgent)) return 'Android';
        if (preg_match('/iPhone|iPad/i', $userAgent)) return 'iOS';
        
        return 'Unknown';
    }

    /**
     * Extract browser from user agent
     */
    private static function getBrowser(string $userAgent): string
    {
        if (preg_match('/Chrome/i', $userAgent)) return 'Chrome';
        if (preg_match('/Firefox/i', $userAgent)) return 'Firefox';
        if (preg_match('/Safari/i', $userAgent)) return 'Safari';
        if (preg_match('/Edge/i', $userAgent)) return 'Edge';
        if (preg_match('/Opera/i', $userAgent)) return 'Opera';
        
        return 'Unknown';
    }
}
