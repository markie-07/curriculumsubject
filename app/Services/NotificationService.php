<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Create a notification for a specific user.
     */
    public static function notify($userId, $type, $title, $message, $data = null)
    {
        return Notification::createForUser($userId, $type, $title, $message, $data);
    }

    /**
     * Create notifications for all admin users.
     */
    public static function notifyAdmins($type, $title, $message, $data = null)
    {
        return Notification::createForAdmins($type, $title, $message, $data);
    }

    /**
     * Create notifications for all users.
     */
    public static function notifyAllUsers($type, $title, $message, $data = null)
    {
        return Notification::createForAllUsers($type, $title, $message, $data);
    }

    /**
     * Curriculum-related notifications
     */
    public static function curriculumCreated($curriculumName, $createdBy)
    {
        self::notifyAdmins(
            'success',
            'New Curriculum Created',
            "A new curriculum '{$curriculumName}' has been created by {$createdBy}.",
            ['type' => 'curriculum_created', 'curriculum_name' => $curriculumName]
        );
    }

    public static function curriculumUpdated($curriculumName, $updatedBy)
    {
        self::notifyAdmins(
            'info',
            'Curriculum Updated',
            "Curriculum '{$curriculumName}' has been updated by {$updatedBy}.",
            ['type' => 'curriculum_updated', 'curriculum_name' => $curriculumName]
        );
    }

    public static function curriculumDeleted($curriculumName, $deletedBy)
    {
        self::notifyAdmins(
            'warning',
            'Curriculum Deleted',
            "Curriculum '{$curriculumName}' has been deleted by {$deletedBy}.",
            ['type' => 'curriculum_deleted', 'curriculum_name' => $curriculumName]
        );
    }

    /**
     * Subject-related notifications
     */
    public static function subjectRemoved($subjectName, $curriculumName, $removedBy)
    {
        self::notifyAdmins(
            'warning',
            'Subject Removed',
            "Subject '{$subjectName}' has been removed from curriculum '{$curriculumName}' by {$removedBy}.",
            ['type' => 'subject_removed', 'subject_name' => $subjectName, 'curriculum_name' => $curriculumName]
        );
    }

    public static function subjectRetrieved($subjectName, $curriculumName, $retrievedBy)
    {
        self::notifyAdmins(
            'success',
            'Subject Retrieved',
            "Subject '{$subjectName}' has been retrieved back to curriculum '{$curriculumName}' by {$retrievedBy}.",
            ['type' => 'subject_retrieved', 'subject_name' => $subjectName, 'curriculum_name' => $curriculumName]
        );
    }

    /**
     * Authentication-related notifications
     */
    public static function loginAttempt($username, $success = true)
    {
        $user = User::where('username', $username)->first();
        if ($user) {
            $type = $success ? 'success' : 'warning';
            $title = $success ? 'Successful Login' : 'Failed Login Attempt';
            $message = $success 
                ? "You have successfully logged in to your account."
                : "There was a failed login attempt on your account.";
            
            self::notify($user->id, $type, $title, $message, ['type' => 'login_attempt', 'success' => $success]);
            
            // Notify admins about failed login attempts for security monitoring
            if (!$success) {
                self::notifyAdminsAboutFailedLogin($user);
            }
        }
    }
    
    /**
     * Notify admins about failed login attempts
     */
    public static function notifyAdminsAboutFailedLogin($user)
    {
        $adminUsers = User::whereIn('role', ['admin', 'super_admin'])->get();
        
        foreach ($adminUsers as $admin) {
            self::notify(
                $admin->id,
                'warning',
                'Failed Login Attempt Detected',
                "Failed login attempt detected for user '{$user->name}' ({$user->username}).",
                [
                    'type' => 'security_alert',
                    'alert_type' => 'failed_login',
                    'target_user_id' => $user->id,
                    'target_user_name' => $user->name,
                    'target_username' => $user->username
                ]
            );
        }
    }

    public static function passwordChanged($userId)
    {
        $user = User::find($userId);
        
        // Notify the user who changed their password
        self::notify(
            $userId,
            'success',
            'Password Changed',
            'Your password has been successfully updated.',
            ['type' => 'password_changed']
        );
        
        // Notify super admins and admins about the password change
        self::notifyAdminsAboutPasswordChange($user);
    }
    
    /**
     * Notify admins and super admins about password changes
     */
    public static function notifyAdminsAboutPasswordChange($user)
    {
        $adminUsers = User::whereIn('role', ['admin', 'super_admin'])
                         ->where('id', '!=', $user->id) // Don't notify the user who made the change
                         ->get();
        
        foreach ($adminUsers as $admin) {
            self::notify(
                $admin->id,
                'warning',
                'User Password Changed',
                "User '{$user->name}' ({$user->username}) has changed their password.",
                [
                    'type' => 'user_password_changed',
                    'changed_user_id' => $user->id,
                    'changed_user_name' => $user->name,
                    'changed_user_role' => $user->role
                ]
            );
        }
    }

    public static function profileUpdated($userId)
    {
        $user = User::find($userId);
        
        // Notify the user who updated their profile
        self::notify(
            $userId,
            'info',
            'Profile Updated',
            'Your profile information has been successfully updated.',
            ['type' => 'profile_updated']
        );
        
        // Notify super admins and admins about the profile change
    }
    
    /**
     * Notify admins and super admins about profile changes
     */
    public static function notifyAdminsAboutProfileChange($user)
    {
        $adminUsers = User::whereIn('role', ['admin', 'super_admin'])
                         ->where('id', '!=', $user->id) // Don't notify the user who made the change
                         ->get();
        
        foreach ($adminUsers as $admin) {
            self::notify(
                $admin->id,
                'info',
                'User Profile Updated',
                "User '{$user->name}' ({$user->username}) has updated their profile information.",
                [
                    'type' => 'user_profile_updated',
                    'updated_user_id' => $user->id,
                    'updated_user_name' => $user->name,
                    'updated_user_email' => $user->email,
                    'updated_user_role' => $user->role
                ]
            );
        }
    }
    
    /**
     * Enhanced profile update notification with change details
     */
    public static function profileUpdatedWithDetails($userId, $oldName, $oldEmail, $newName, $newEmail)
    {
        $user = User::find($userId);
        
        // Notify the user who updated their profile
        self::notify(
            $userId,
            'info',
            'Profile Updated',
            'Your profile information has been successfully updated.',
            ['type' => 'profile_updated']
        );
        
        // Notify super admins and admins about the profile change with details
        $adminUsers = User::whereIn('role', ['admin', 'super_admin'])
                         ->where('id', '!=', $user->id)
                         ->get();
        
        $changes = [];
        if ($oldName !== $newName) {
            $changes[] = "Name: '{$oldName}' → '{$newName}'";
        }
        if ($oldEmail !== $newEmail) {
            $changes[] = "Email: '{$oldEmail}' → '{$newEmail}'";
        }
        
        $changeDetails = implode(', ', $changes);
        
        foreach ($adminUsers as $admin) {
            self::notify(
                $admin->id,
                'info',
                'User Profile Updated',
                "User '{$user->name}' ({$user->username}) has updated their profile. Changes: {$changeDetails}",
                [
                    'type' => 'user_profile_updated_detailed',
                    'updated_user_id' => $user->id,
                    'updated_user_name' => $user->name,
                    'updated_user_role' => $user->role,
                    'changes' => [
                        'old_name' => $oldName,
                        'new_name' => $newName,
                        'old_email' => $oldEmail,
                        'new_email' => $newEmail
                    ],
                    'change_summary' => $changeDetails
                ]
            );
        }
    }
    
    /**
     * Export-related notifications
     */
    public static function exportCompleted($userId, $exportType, $fileName)
    {
        self::notify(
            $userId,
            'success',
            'Export Completed',
            "Your {$exportType} export has been completed successfully. File: {$fileName}",
            ['type' => 'export_completed', 'export_type' => $exportType, 'file_name' => $fileName]
        );
    }

    /**
     * System notifications
     */
    public static function systemMaintenance($message)
    {
        self::notifyAllUsers(
            'info',
            'System Maintenance',
            $message,
            ['type' => 'system_maintenance']
        );
    }

    public static function complianceCheck($curriculumName, $status, $issues = [])
    {
        $type = $status === 'passed' ? 'success' : 'warning';
        $title = $status === 'passed' ? 'Compliance Check Passed' : 'Compliance Issues Found';
        $message = $status === 'passed' 
            ? "Curriculum '{$curriculumName}' has passed the CHED compliance check."
            : "Curriculum '{$curriculumName}' has compliance issues that need attention.";

        self::notifyAdmins($type, $title, $message, [
            'type' => 'compliance_check',
            'curriculum_name' => $curriculumName,
            'status' => $status,
            'issues' => $issues
        ]);

    }

    /**
     * User management notifications
     */
    public static function userCreated($newUser, $createdBy)
    {
        $adminUsers = User::whereIn('role', ['admin', 'super_admin'])->get();

        foreach ($adminUsers as $admin) {
            self::notify(
                $admin->id,
                'success',
                'New User Created',
                "A new user '{$newUser->name}' ({$newUser->username}) with role '{$newUser->role}' has been created by {$createdBy}.",
                [
                    'type' => 'user_created',
                    'new_user_id' => $newUser->id,
                    'new_user_name' => $newUser->name,
                    'new_user_role' => $newUser->role,
                    'created_by' => $createdBy
                ]
            );
        }
    }

    public static function userDeleted($deletedUser, $deletedBy)
    {
        $adminUsers = User::whereIn('role', ['admin', 'super_admin'])->get();

        foreach ($adminUsers as $admin) {
            self::notify(
                $admin->id,
                'warning',
                'User Account Deleted',
                "User account '{$deletedUser->name}' ({$deletedUser->username}) has been deleted by {$deletedBy}.",
                [
                    'type' => 'user_deleted',
                    'deleted_user_name' => $deletedUser->name,
                    'deleted_user_role' => $deletedUser->role,
                    'deleted_by' => $deletedBy
                ]
            );
        }
    }


    /**
     * System activity notifications
     */
    public static function systemActivity($activityType, $description, $performedBy, $data = [])
    {
        $adminUsers = User::whereIn('role', ['admin', 'super_admin'])->get();

        foreach ($adminUsers as $admin) {
            self::notify(
                $admin->id,
                'info',
                'System Activity',
                "{$description} by {$performedBy}",
                array_merge([
                    'type' => 'system_activity',
                    'activity_type' => $activityType,
                    'performed_by' => $performedBy
                ], $data)
            );
        }
    }
}