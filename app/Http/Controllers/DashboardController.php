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

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role
     */
    public function index()
    {
        try {
            $user = Auth::user();
            
            // Debug logging
            \Log::info('Dashboard accessed', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'timestamp' => now()
            ]);
            
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
     * Get comprehensive system statistics
     */
    private function getSystemStats()
    {
        // Log that we're fetching stats
        \Log::info('Fetching dashboard statistics');
        
        $stats = [
            // Curriculum Statistics - Based on year_level column
            'curriculum_senior_high' => $this->getCurriculumCount('Senior High'),
            'curriculum_college' => $this->getCurriculumCount('College'),
            'total_curriculums' => Curriculum::count(),
            
            // Subject Statistics  
            'total_subjects' => Subject::count(),
            'active_subjects' => Subject::count(), // All subjects are considered active if no status column
            
            // Pre-requisite Statistics
            'total_prerequisites' => $this->getPrerequisiteCount(),
            
            // Mapping History Statistics (Curriculum-Subject relationships)
            'total_mapping_history' => $this->getMappingHistoryCount(),
            
            // Subject Offering History (Removed Subjects)
            'removed_subjects' => $this->getRemovedSubjectsCount(),
            
            // Subject Equivalency Statistics
            'total_equivalencies' => $this->getEquivalencyCount(),
            
            // Export Statistics - Based on actual activity logs
            'curriculum_exports' => EmployeeActivityLog::where('activity_type', 'export')->count(),
            'exports_this_month' => EmployeeActivityLog::where('activity_type', 'export')
                                                      ->whereMonth('created_at', now()->month)
                                                      ->count(),
            'total_exports' => EmployeeActivityLog::where('activity_type', 'export')->count(),
            
            // Employee Statistics - Based on actual user roles and status
            'employees_active' => $this->getActiveEmployeesCount(),
            'employees_inactive' => $this->getInactiveEmployeesCount(),
            'total_employees' => User::where('role', 'employee')->count(),
            
            // System Statistics
            'total_users' => User::count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'activities_today' => EmployeeActivityLog::whereDate('created_at', today())->count(),
            'activities_this_week' => EmployeeActivityLog::where('created_at', '>=', now()->subWeek())->count(),
        ];
        
        // Log the final stats for debugging
        \Log::info('Dashboard statistics:', $stats);
        
        return $stats;
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
     * Get recent activities for dashboard
     */
    private function getRecentActivities()
    {
        try {
            return EmployeeActivityLog::with('user')
                                     ->whereHas('user', function($query) {
                                         $query->where('role', 'employee');
                                     })
                                     ->orderBy('created_at', 'desc')
                                     ->limit(5)
                                     ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
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
