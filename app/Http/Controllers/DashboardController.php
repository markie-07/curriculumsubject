<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Curriculum;
use App\Models\Subject;
use App\Models\ExportHistory;
use App\Models\EmployeeActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role
     */
    public function index()
    {
        try {
            $user = Auth::user();
            
            // Update user activity when visiting dashboard
            if ($user) {
                $user->last_activity = now();
                $user->save();
            }
            
            // Redirect employees directly to curriculum export tool
            if ($user->role === 'employee') {
                return redirect()->route('curriculum_export_tool');
            }
            
            // Get dashboard data based on role for admins
            $dashboardData = $this->getDashboardData($user);
            
            return view('dashboard', compact('user', 'dashboardData'));
            
        } catch (\Exception $e) {
            \Log::error('Dashboard error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return a simple error view or redirect
            return response()->view('errors.500', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get dashboard data based on user role
     */
    private function getDashboardData($user)
    {
        $data = [
            'role' => $user->role,
            'welcome_message' => $this->getWelcomeMessage($user),
            'stats' => $this->getSystemStats(),
            'recent_activities' => $this->getRecentActivities(),
        ];

        if ($user->isSuperAdmin()) {
            $data['permissions'] = ['manage_users', 'manage_system', 'view_all_data'];
        } elseif ($user->isAdmin()) {
            $data['permissions'] = ['manage_content', 'view_reports'];
        } else {
            $data['permissions'] = ['view_content'];
        }

        return $data;
    }

    /**
     * Get comprehensive system statistics with caching and optimized queries
     */
    private function getSystemStats()
    {
        // Cache dashboard stats for 5 seconds to allow real-time active user tracking
        return Cache::remember('dashboard_stats', 5, function() {
            try {
                // Optimized curriculum statistics with single query
                $curriculumStats = DB::table('curriculums')
                    ->selectRaw('
                        COUNT(*) as total_curriculums,
                        SUM(CASE WHEN year_level = "Senior High" THEN 1 ELSE 0 END) as curriculum_senior_high,
                        SUM(CASE WHEN year_level = "College" THEN 1 ELSE 0 END) as curriculum_college
                    ')
                    ->first();

                // Optimized user statistics with single query
                $userStats = DB::table('users')
                    ->selectRaw('
                        COUNT(*) as total_users,
                        SUM(CASE WHEN role = "admin" THEN 1 ELSE 0 END) as total_admins,
                        SUM(CASE WHEN role = "employee" AND status = "active" THEN 1 ELSE 0 END) as employees_active,
                        SUM(CASE WHEN role = "employee" AND status = "inactive" THEN 1 ELSE 0 END) as employees_inactive,
                        SUM(CASE WHEN role = "employee" THEN 1 ELSE 0 END) as total_employees
                    ')
                    ->first();

                // Optimized activity statistics with single query
                $activityStats = DB::table('employee_activity_logs')
                    ->selectRaw('
                        SUM(CASE WHEN activity_type = "export" THEN 1 ELSE 0 END) as curriculum_exports,
                        SUM(CASE WHEN activity_type = "export" AND MONTH(created_at) = ? THEN 1 ELSE 0 END) as exports_this_month,
                        SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as activities_today,
                        SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as activities_this_week
                    ', [now()->month, now()->subWeek()])
                    ->first();

                // Get weekly activity data for the chart (last 7 days)
                $weeklyActivities = $this->getWeeklyActivityData();

                // Get other statistics efficiently
                $subjectCount = Subject::count();
                
                $stats = [
                    // Curriculum Statistics
                    'curriculum_senior_high' => $curriculumStats->curriculum_senior_high ?? 0,
                    'curriculum_college' => $curriculumStats->curriculum_college ?? 0,
                    'total_curriculums' => $curriculumStats->total_curriculums ?? 0,
                    
                    // Subject Statistics  
                    'total_subjects' => $subjectCount,
                    'active_subjects' => $subjectCount,
                    
                    // Other Statistics (cached separately if needed)
                    'total_prerequisites' => $this->getPrerequisiteCount(),
                    'total_mapping_history' => $this->getMappingHistoryCount(),
                    'removed_subjects' => $this->getRemovedSubjectsCount(),
                    'total_equivalencies' => $this->getEquivalencyCount(),
                    
                    // Export Statistics
                    'curriculum_exports' => $activityStats->curriculum_exports ?? 0,
                    'exports_this_month' => $activityStats->exports_this_month ?? 0,
                    'total_exports' => $activityStats->curriculum_exports ?? 0,
                    
                    // Employee Statistics
                    'employees_active' => $userStats->employees_active ?? 0,
                    'employees_inactive' => $userStats->employees_inactive ?? 0,
                    'total_employees' => $userStats->total_employees ?? 0,
                    
                    // System Statistics
                    'total_users' => $userStats->total_users ?? 0,
                    'total_admins' => $userStats->total_admins ?? 0,
                    'activities_today' => $activityStats->activities_today ?? 0,
                    'activities_this_week' => $activityStats->activities_this_week ?? 0,
                    
                    // Real-time Active Users (last 15 minutes)
                    'active_users_online' => $this->getActiveUsersCount(),
                    
                    // Weekly activity data for chart
                    'weekly_activities' => $weeklyActivities,
                ];
                
                return $stats;
                
            } catch (\Exception $e) {
                // Log error but don't break the dashboard
                \Log::error('Dashboard stats error: ' . $e->getMessage());
                return $this->getDefaultStats();
            }
        });
    }

    /**
     * Get weekly activity data for the last 7 days
     */
    private function getWeeklyActivityData()
    {
        try {
            $weeklyData = [];
            
            // Get data for the last 7 days (Monday to Sunday)
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $dayName = $date->format('D'); // Mon, Tue, Wed, etc.
                
                $count = DB::table('employee_activity_logs')
                    ->whereDate('created_at', $date->format('Y-m-d'))
                    ->count();
                
                $weeklyData[$dayName] = $count;
            }
            
            return $weeklyData;
            
        } catch (\Exception $e) {
            \Log::error('Error fetching weekly activity data: ' . $e->getMessage());
            // Return default data if there's an error
            return [
                'Mon' => 0, 'Tue' => 0, 'Wed' => 0, 'Thu' => 0, 
                'Fri' => 0, 'Sat' => 0, 'Sun' => 0
            ];
        }
    }

    /**
     * Get default stats in case of database errors
     */
    private function getDefaultStats()
    {
        return [
            'curriculum_senior_high' => 0,
            'curriculum_college' => 0,
            'total_curriculums' => 0,
            'total_subjects' => 0,
            'active_subjects' => 0,
            'total_prerequisites' => 0,
            'total_mapping_history' => 0,
            'removed_subjects' => 0,
            'total_equivalencies' => 0,
            'curriculum_exports' => 0,
            'exports_this_month' => 0,
            'total_exports' => 0,
            'employees_active' => 0,
            'employees_inactive' => 0,
            'total_employees' => 0,
            'total_users' => 0,
            'total_admins' => 0,
            'activities_today' => 0,
            'activities_this_week' => 0,
            'weekly_activities' => [
                'Mon' => 0, 'Tue' => 0, 'Wed' => 0, 'Thu' => 0, 
                'Fri' => 0, 'Sat' => 0, 'Sun' => 0
            ],
        ];
    }

    /**
     * Get curriculum count by level
     */
    private function getCurriculumCount($level)
    {
        try {
            // Use the year_level column to properly count curriculums
            $count = Curriculum::where('year_level', $level)->count();
            
            // Log the count for debugging
            \Log::info("Curriculum count for {$level}: {$count}");
            
            return $count;
        } catch (\Exception $e) {
            \Log::error("Error counting curriculums for {$level}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get prerequisite count
     */
    private function getPrerequisiteCount()
    {
        // Count subjects that have prerequisites defined
        try {
            return Subject::whereNotNull('prerequisites')
                         ->where('prerequisites', '!=', '')
                         ->count();
        } catch (\Exception $e) {
            // If prerequisites column doesn't exist, return 0
            return 0;
        }
    }

    /**
     * Get mapping history count (curriculum-subject relationships)
     */
    private function getMappingHistoryCount()
    {
        try {
            // First try to get from curriculum_subject pivot table (most likely to exist)
            return DB::table('curriculum_subject')->count();
        } catch (\Exception $e) {
            // If pivot table doesn't exist, try mapping_history table
            try {
                return DB::table('mapping_history')->count();
            } catch (\Exception $e2) {
                // If no mapping tables exist, return 0
                return 0;
            }
        }
    }

    /**
     * Get removed subjects count from subject offering history
     */
    private function getRemovedSubjectsCount()
    {
        try {
            return DB::table('subject_offering_history')
                    ->where('status', 'removed')
                    ->orWhere('action', 'removed')
                    ->count();
        } catch (\Exception $e) {
            // If table doesn't exist, return 0 as we don't have a status column
            return 0;
        }
    }

    /**
     * Get subject equivalency count
     */
    private function getEquivalencyCount()
    {
        try {
            return DB::table('subject_equivalencies')->count();
        } catch (\Exception $e) {
            // If equivalencies table doesn't exist, return 0
            return 0;
        }
    }

    /**
     * Get recent activities for dashboard with caching
     */
    private function getRecentActivities()
    {
        return Cache::remember('dashboard_recent_activities', 120, function() {
            try {
                return EmployeeActivityLog::with(['user:id,name,email,role'])
                                         ->whereHas('user', function($query) {
                                             $query->where('role', 'employee');
                                         })
                                         ->select(['id', 'user_id', 'activity_type', 'description', 'created_at'])
                                         ->orderBy('created_at', 'desc')
                                         ->limit(5)
                                         ->get();
            } catch (\Exception $e) {
                \Log::error('Error fetching recent activities: ' . $e->getMessage());
                return collect([]);
            }
        });
    }

    /**
     * Safe count for models that might not have tables
     */
    private function safeCount($modelName)
    {
        try {
            $modelClass = "App\\Models\\{$modelName}";
            if (class_exists($modelClass)) {
                return $modelClass::count();
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Safe count with condition for models that might not have tables
     */
    private function safeCountWithCondition($modelName, $callback)
    {
        try {
            $modelClass = "App\\Models\\{$modelName}";
            if (class_exists($modelClass)) {
                $query = $modelClass::query();
                return $callback($query)->count();
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get active employees count
     */
    private function getActiveEmployeesCount()
    {
        try {
            return User::where('role', 'employee')->where('status', 'active')->count();
        } catch (\Exception $e) {
            // If status column doesn't exist, count all employees as active
            return User::where('role', 'employee')->count();
        }
    }

    /**
     * Get inactive employees count
     */
    private function getInactiveEmployeesCount()
    {
        try {
            return User::where('role', 'employee')->where('status', 'inactive')->count();
        } catch (\Exception $e) {
            // If status column doesn't exist, return 0
            return 0;
        }
    }

    /**
     * Get count of users who have been active in the last 5 seconds
     */
    private function getActiveUsersCount()
    {
        try {
            // Count users who have been active in the last 5 seconds for real-time tracking
            $activeThreshold = now()->subSeconds(5);
            
            return User::whereNotNull('last_activity')
                      ->where('last_activity', '>=', $activeThreshold)
                      ->count();
        } catch (\Exception $e) {
            \Log::error('Error counting active users: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get welcome message based on user role
     */
    private function getWelcomeMessage($user)
    {
        switch ($user->role) {
            case 'super_admin':
                return "Welcome back, Super Administrator! You have full system access.";
            case 'admin':
                return "Welcome back, Administrator! Manage your sections efficiently.";
            case 'employee':
                return "Welcome! You can access the Curriculum Export Tool to export curriculum data.";
            default:
                return "Welcome to the Student Management System!";
        }
    }
}
