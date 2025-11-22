<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrerequisiteController;
use App\Http\Controllers\EquivalencyToolController;
use App\Http\Controllers\CurriculumExportToolController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SubjectExportController;
use App\Http\Controllers\CurriculumVersionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CurriculumHistoryController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/otp-verify', [AuthController::class, 'showOtpForm'])->name('otp.verify');
    Route::post('/otp-verify', [AuthController::class, 'verifyOtp'])->name('otp.verify.submit');
    Route::post('/otp-resend', [AuthController::class, 'resendOtp'])->name('otp.resend');
    
    // CSRF token refresh route
    Route::get('/csrf-token', function () {
        return response()->json(['csrf_token' => csrf_token()]);
    });
});
// Debug routes (temporary)
Route::get('/debug', function () {
    return response()->json([
        'status' => 'Laravel is working',
        'user' => auth()->user() ? auth()->user()->toArray() : 'Not authenticated',
    ]);
});

Route::get('/debug-redirect', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return response()->json([
            'authenticated' => true,
            'user_role' => $user->role,
            'user_email' => $user->email,
            'is_employee' => $user->isEmployee(),
            'dashboard_route' => route('dashboard'),
            'curriculum_export_route' => route('curriculum_export_tool'),
            'intended_url' => session()->get('url.intended', 'none')
        ]);
    }
    return response()->json(['authenticated' => false]);
})->middleware('auth');

Route::get('/test-view', function () {
    return view('dashboard', [
        'user' => (object)['name' => 'Test User', 'role' => 'admin'],
        'dashboardData' => [
            'role' => 'admin',
            'welcome_message' => 'Test message',
            'stats' => [
                'curriculum_senior_high' => 0,
                'curriculum_college' => 0,
                'total_curriculums' => 0,
                'total_subjects' => 0,
                'total_prerequisites' => 0,
                'total_mapping_history' => 0,
                'removed_subjects' => 0,
                'total_equivalencies' => 0,
                'curriculum_exports' => 0,
                'employees_active' => 0,
                'employees_inactive' => 0,
            ],
            'recent_activities' => collect([])
        ]
    ]);
});

// Protected Routes
Route::middleware(['auth', 'prevent.back'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/validate-email', [ProfileController::class, 'validateEmail'])->name('profile.validate-email');

    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/recent', [NotificationController::class, 'getRecent'])->name('notifications.recent');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    
    // Test Notification Route
    Route::get('/test-notifications', function () {
        return view('test_notifications');
    })->name('test.notifications');
    
    // Debug notifications route
    Route::get('/debug-notifications', function () {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Not authenticated']);
        }
        
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->limit(10)->get();
        $unreadCount = $user->notifications()->unread()->count();
        
        return response()->json([
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'notifications_count' => $user->notifications()->count(),
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
            'all_notifications_count' => \App\Models\Notification::count()
        ]);
    })->name('debug.notifications');

    // Curriculum Export Tool - Accessible to all authenticated users (employees, admin, super admin)
    Route::get('/curriculum_export_tool', [CurriculumExportToolController::class, 'index'])->name('curriculum_export_tool');
    Route::post('/curriculum_export_tool', [CurriculumExportToolController::class, 'store'])->name('curriculum_export_tool.store');
    Route::get('/api/curriculum/{id}/subjects', [CurriculumExportToolController::class, 'getCurriculumSubjects'])->name('curriculum.subjects');
    Route::get('/subjects/{subjectId}/export-pdf', [SubjectExportController::class, 'exportPdf'])->name('subjects.export-pdf');
    Route::get('/curriculum/{id}/export-pdf', [CurriculumExportToolController::class, 'exportPdf'])->name('curriculum.export-pdf');

    // Admin-only routes
    Route::middleware('admin')->group(function () {
        Route::get('/curriculum_builder', function () {
            return view('curriculum_builder');
        })->name('curriculum_builder');

        Route::get('/official_curriculum', function () {
            return view('official_curriculum');
        })->name('official_curriculum');

        Route::get('/subject_mapping', function () {
            return view('subject_mapping');
        })->name('subject_mapping');

        Route::get('/pre_requisite', function () {
            $curriculums = \App\Models\Curriculum::all();
            return view('pre_requisite', compact('curriculums'));
        })->name('pre_requisite');

        Route::get('/grade-setup', [GradeController::class, 'setup'])->name('grade_setup');

        Route::get('/equivalency_tool', function () {
            $subjects = \App\Models\Subject::all();
            $equivalencies = \App\Models\Equivalency::with('equivalentSubject')->get();
            return view('equivalency_tool', compact('subjects', 'equivalencies'));
        })->name('equivalency_tool');


        // CHED Compliance Validator
        Route::get('/compliance-validator', function () {
            return view('compliance_validator');
        })->name('compliance.validator');

        Route::get('/subject_mapping_history', function () {
            return view('subject_mapping_history');
        })->name('subject_mapping_history');

        Route::get('/course-builder', function () {
            return view('course_builder');
        })->name('course_builder');

        // Curriculum History API Routes
        Route::prefix('api/curriculum-history')->group(function () {
            Route::get('/{curriculumId}/versions', [CurriculumHistoryController::class, 'getVersions']);
            Route::get('/{curriculumId}/versions/{versionId}', [CurriculumHistoryController::class, 'getVersionDetails']);
            Route::post('/{curriculumId}/snapshot', [CurriculumHistoryController::class, 'createSnapshot']);
            Route::get('/{curriculumId}/compare/{version1Id}/{version2Id}', [CurriculumHistoryController::class, 'compareVersions']);
        });

        // Employee Management Routes (Admin and Super Admin only)
        Route::resource('employees', EmployeeController::class)->except(['show']);
        
        // Employee Activity Logs and Status Management
        Route::get('/employees/{id}/activity-logs', [EmployeeController::class, 'activityLogs'])->name('employees.activity-logs');
        Route::patch('/employees/{id}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('employees.toggle-status');
        Route::get('/employee-activities', [EmployeeController::class, 'allActivities'])->name('employees.all-activities');
        Route::get('/employee-activities/export', [EmployeeController::class, 'exportActivities'])->name('employees.export-activities');
    });
}); // End of auth middleware group