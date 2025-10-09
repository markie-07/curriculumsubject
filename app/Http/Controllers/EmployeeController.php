<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EmployeeActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display employee management page
     */
    public function index()
    {
        $employees = User::where('role', 'employee')
                        ->withCount('activityLogs')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        
        $stats = ActivityLogService::getActivityStats();
        
        return view('employees', compact('employees', 'stats'));
    }

    /**
     * Show create employee form
     */
    public function create()
    {
        return view('employees');
    }

    /**
     * Store new employee
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'employee',
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully!');
    }

    /**
     * Show edit employee form
     */
    public function edit($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        return view('employees', compact('employee'));
    }

    /**
     * Update employee
     */
    public function update(Request $request, $id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $employee->update($updateData);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
    }

    /**
     * Delete employee
     */
    public function destroy($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully!');
    }

    /**
     * Show employee activity logs
     */
    public function activityLogs($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        
        $activities = $employee->activityLogs()
                              ->orderBy('created_at', 'desc')
                              ->paginate(20);
        
        $stats = ActivityLogService::getActivityStats($employee);
        
        return view('employees.activity-logs', compact('employee', 'activities', 'stats'));
    }

    /**
     * Toggle employee status
     */
    public function toggleStatus($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        
        $newStatus = $employee->status === 'active' ? 'inactive' : 'active';
        $employee->update(['status' => $newStatus]);
        
        // Log the status change
        ActivityLogService::log(
            'status_change',
            "Employee status changed to {$newStatus}",
            [
                'old_status' => $employee->status === 'active' ? 'inactive' : 'active',
                'new_status' => $newStatus,
                'changed_by' => auth()->user()->name,
            ],
            $employee
        );
        
        $message = $newStatus === 'active' 
            ? 'Employee activated successfully!' 
            : 'Employee deactivated successfully!';
            
        return redirect()->route('employees.index')->with('success', $message);
    }

    /**
     * Get all employee activities (for admin dashboard)
     */
    public function allActivities()
    {
        $activities = ActivityLogService::getAllEmployeeActivities(50);
        $stats = ActivityLogService::getActivityStats();
        
        return view('employees.all-activities', compact('activities', 'stats'));
    }

    /**
     * Export employee activity report
     */
    public function exportActivities(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        
        $activities = EmployeeActivityLog::with('user')
                                       ->whereHas('user', function($query) {
                                           $query->where('role', 'employee');
                                       })
                                       ->dateRange($startDate, $endDate)
                                       ->orderBy('created_at', 'desc')
                                       ->get();
        
        // Log this export activity
        ActivityLogService::logExport('employee_activity_report', "activities_{$startDate}_to_{$endDate}.csv");
        
        return response()->streamDownload(function() use ($activities) {
            $handle = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($handle, [
                'Employee Name',
                'Username',
                'Activity Type',
                'Description',
                'Date & Time',
                'IP Address',
                'Browser'
            ]);
            
            // CSV data
            foreach ($activities as $activity) {
                $browser = $activity->metadata['browser']['browser'] ?? 'Unknown';
                
                fputcsv($handle, [
                    $activity->user->name,
                    $activity->user->username,
                    ucfirst($activity->activity_type),
                    $activity->formatted_description,
                    $activity->created_at->format('Y-m-d H:i:s'),
                    $activity->ip_address,
                    $browser
                ]);
            }
            
            fclose($handle);
        }, "employee_activities_{$startDate}_to_{$endDate}.csv", [
            'Content-Type' => 'text/csv',
        ]);
    }
}
