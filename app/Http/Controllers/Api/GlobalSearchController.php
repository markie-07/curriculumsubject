<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curriculum;
use App\Models\Subject;
use App\Models\User;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        try {
            $query = $request->input('query');
            
            if (strlen($query) < 2) {
                return response()->json(['results' => []]);
            }
            
            $results = [];
        
        // Search in Curriculums
        $curriculums = Curriculum::where('curriculum', 'LIKE', "%{$query}%")
            ->orWhere('program_code', 'LIKE', "%{$query}%")
            ->limit(3)
            ->get();
            
        foreach ($curriculums as $curriculum) {
            $results[] = [
                'type' => 'curriculum',
                'name' => $curriculum->curriculum . ' (' . $curriculum->program_code . ')',
                'url' => route('curriculum_builder')
            ];
        }
        
        // Search in Subjects
        $subjects = Subject::where('subject_name', 'LIKE', "%{$query}%")
            ->orWhere('subject_code', 'LIKE', "%{$query}%")
            ->limit(3)
            ->get();
            
        foreach ($subjects as $subject) {
            $results[] = [
                'type' => 'subject',
                'name' => $subject->subject_name . ' (' . $subject->subject_code . ')',
                'url' => route('subject_mapping')
            ];
        }
        
        // Search in Employees (Users with employee role)
        $employees = User::where('role', 'employee')
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('username', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->limit(3)
            ->get();
            
        foreach ($employees as $employee) {
            $results[] = [
                'type' => 'employee',
                'name' => $employee->name . ' (' . $employee->username . ')',
                'url' => route('employees.index')
            ];
        }
        
        // Limit total results to 10
        $results = array_slice($results, 0, 10);
        
        return response()->json(['results' => $results]);
        
        } catch (\Exception $e) {
            \Log::error('GlobalSearch Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Search failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function quickSearch($type)
    {
        $results = [];
        
        switch ($type) {
            case 'curriculum':
                $curriculums = Curriculum::orderBy('curriculum')
                    ->limit(10)
                    ->get();
                    
                foreach ($curriculums as $curriculum) {
                    $results[] = [
                        'type' => 'curriculum',
                        'name' => $curriculum->curriculum . ' (' . $curriculum->program_code . ')',
                        'url' => route('curriculum_builder')
                    ];
                }
                break;
                
            case 'subject':
                $subjects = Subject::orderBy('subject_name')
                    ->limit(10)
                    ->get();
                    
                foreach ($subjects as $subject) {
                    $results[] = [
                        'type' => 'subject',
                        'name' => $subject->subject_name . ' (' . $subject->subject_code . ')',
                        'url' => route('subject_mapping')
                    ];
                }
                break;
                
            case 'employee':
                $employees = User::where('role', 'employee')
                    ->orderBy('name')
                    ->limit(10)
                    ->get();
                    
                foreach ($employees as $employee) {
                    $results[] = [
                        'type' => 'employee',
                        'name' => $employee->name . ' (' . $employee->username . ')',
                        'url' => route('employees.index')
                    ];
                }
                break;
        }
        
        return response()->json(['results' => $results]);
    }
}
