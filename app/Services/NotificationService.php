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
        }
    }

    public static function passwordChanged($userId)
    {
        self::notify(
            $userId,
            'success',
            'Password Changed',
            'Your password has been successfully updated.',
            ['type' => 'password_changed']
        );
    }

    public static function profileUpdated($userId)
    {
        self::notify(
            $userId,
            'info',
            'Profile Updated',
            'Your profile information has been successfully updated.',
            ['type' => 'profile_updated']
        );
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
}
