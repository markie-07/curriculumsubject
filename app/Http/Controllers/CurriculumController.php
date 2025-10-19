<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use App\Models\Subject;
use App\Models\Notification;
use App\Services\CurriculumVersionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CurriculumController extends Controller
{
    /**
     * Retrieves all curriculums formatted for a dropdown selector.
     */
    public function index()
    {
        $curriculums = Curriculum::withCount('subjects')
            ->orderBy('year_level')
            ->orderBy('curriculum')
            ->get()
            ->map(function ($curriculum) {
                return [
                    'id' => $curriculum->id,
                    'curriculum_name' => $curriculum->curriculum,
                    'program_code' => $curriculum->program_code,
                    'academic_year' => $curriculum->academic_year,
                    'year_level' => $curriculum->year_level,
                    'created_at' => $curriculum->created_at,
                    'subjects_count' => $curriculum->subjects_count,
                    'status' => $curriculum->subjects_count > 0 ? 'active' : 'inactive'
                ];
            });
        return response()->json($curriculums);
    }

    /**
     * Stores a new curriculum.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'curriculum' => 'required|string|max:255',
            'programCode' => 'required|string|max:255|unique:curriculums,program_code',
            'academicYear' => 'required|string|max:255',
            'yearLevel' => 'required|in:Senior High,College',
        ]);

        $curriculum = Curriculum::create([
            'curriculum' => $validated['curriculum'],
            'program_code' => $validated['programCode'],
            'academic_year' => $validated['academicYear'],
            'year_level' => $validated['yearLevel'],
        ]);

        // Create database notification for admins
        if (Auth::check()) {
            Notification::createForAdmins(
                'success',
                'New Curriculum Created',
                'Curriculum "' . $curriculum->curriculum . '" has been created by ' . Auth::user()->name,
                ['curriculum_id' => $curriculum->id, 'action' => 'created']
            );
        }

        session()->flash('success', 'Curriculum "' . $curriculum->curriculum . '" has been created successfully!');
        
        // Also trigger notification for AJAX requests
        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Curriculum created successfully!', 
                'curriculum' => $curriculum,
                'notification' => [
                    'type' => 'success',
                    'title' => 'Curriculum Added!',
                    'message' => 'Curriculum "' . $curriculum->curriculum . '" has been created successfully!'
                ]
            ], 201);
        }
        
        return response()->json(['message' => 'Curriculum created successfully!', 'curriculum' => $curriculum], 201);
    }

    /**
     * Updates an existing curriculum.
     */
    public function update(Request $request, $id)
    {
        $curriculum = Curriculum::findOrFail($id);
        $validated = $request->validate([
            'curriculum' => 'required|string|max:255',
            'programCode' => 'required|string|max:255|unique:curriculums,program_code,' . $curriculum->id,
            'academicYear' => 'required|string|max:255',
            'yearLevel' => 'required|in:Senior High,College',
        ]);
        
        $curriculum->update([
            'curriculum' => $validated['curriculum'],
            'program_code' => $validated['programCode'],
            'academic_year' => $validated['academicYear'],
            'year_level' => $validated['yearLevel'],
        ]);

        // Create database notification for admins
        if (Auth::check()) {
            Notification::createForAdmins(
                'info',
                'Curriculum Updated',
                'Curriculum "' . $curriculum->curriculum . '" has been updated by ' . Auth::user()->name,
                ['curriculum_id' => $curriculum->id, 'action' => 'updated']
            );
        }

        session()->flash('success', 'Curriculum "' . $curriculum->curriculum . '" has been updated successfully!');
        
        // Also trigger notification for AJAX requests
        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Curriculum updated successfully!', 
                'curriculum' => $curriculum,
                'notification' => [
                    'type' => 'success',
                    'title' => 'Curriculum Updated!',
                    'message' => 'Curriculum "' . $curriculum->curriculum . '" has been updated successfully!'
                ]
            ]);
        }
        
        return response()->json(['message' => 'Curriculum updated successfully!', 'curriculum' => $curriculum]);
    }

    /**
     * Deletes a curriculum.
     */
    public function destroy($id)
    {
        $curriculum = Curriculum::findOrFail($id);
        $curriculumName = $curriculum->curriculum;
        $curriculum->delete();
        session()->flash('success', 'Curriculum "' . $curriculumName . '" has been deleted successfully!');
        
        // Also trigger notification for AJAX requests
        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Curriculum deleted successfully!',
                'notification' => [
                    'type' => 'success',
                    'title' => 'Curriculum Deleted!',
                    'message' => 'Curriculum "' . $curriculumName . '" has been deleted successfully!'
                ]
            ]);
        }
        
        return response()->json(['message' => 'Curriculum deleted successfully!']);
    }

    /**
     * Retrieves data for a specific curriculum, including all available subjects for mapping.
     */
    public function getCurriculumData($id)
    {
        try {
            $curriculum = Curriculum::with('subjects')->findOrFail($id);
            $allSubjects = Subject::all(); 

            return response()->json([
                'curriculum' => $curriculum,
                'allSubjects' => $allSubjects,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching curriculum data: ' . $e->getMessage());
            return response()->json([
                'message' => 'A database error occurred while fetching curriculum data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Saves the subject mapping for a curriculum.
     */
public function saveSubjects(Request $request)
{
    $validated = $request->validate([
        'curriculumId' => 'required|exists:curriculums,id',
        'curriculumData' => 'required|array',
    ]);

    $curriculum = Curriculum::findOrFail($validated['curriculumId']);
    
    // Get existing subjects before clearing to track changes
    $existingSubjects = $curriculum->subjects()->get()->keyBy('id');
    
    // Use a transaction to ensure data integrity
    DB::transaction(function () use ($curriculum, $validated, $existingSubjects) {
        $newSubjectMappings = [];
        foreach ($validated['curriculumData'] as $data) {
            if (empty($data['subjects'])) {
                continue;
            }
            
            foreach ($data['subjects'] as $subjectData) {
                $subject = Subject::where('subject_code', $subjectData['subject_code'])->first();

                if ($subject) {
                    $newSubjectMappings[$subject->id] = [
                        'year' => $data['year'],
                        'semester' => $data['semester'],
                    ];
                }
            }
        }
        
        // Detach all subjects first
        $curriculum->subjects()->detach();

        // Re-attach subjects with new mappings
        foreach ($newSubjectMappings as $subjectId => $mapping) {
            $curriculum->subjects()->attach($subjectId, $mapping);
        }

        $newSubjectIds = collect(array_keys($newSubjectMappings));

        // Identify added subjects
        $addedSubjects = $newSubjectIds->diff($existingSubjects->keys());
        foreach ($addedSubjects as $subjectId) {
            $subject = Subject::find($subjectId);
            CurriculumVersionService::createSnapshotOnSubjectAdd(
                $curriculum->id, 
                $subject->subject_name
            );
        }

        // Identify removed subjects
        $removedSubjects = $existingSubjects->keys()->diff($newSubjectIds);
        foreach ($removedSubjects as $subjectId) {
            $subject = $existingSubjects[$subjectId];
            CurriculumVersionService::createSnapshotOnSubjectRemove(
                $curriculum->id, 
                $subject->subject_name,
                [
                    'subject_name' => $subject->subject_name,
                    'subject_code' => $subject->subject_code,
                    'subject_type' => $subject->subject_type,
                    'subject_unit' => $subject->subject_unit,
                    'year' => $subject->pivot->year,
                    'semester' => $subject->pivot->semester,
                ]
            );
        }

        // Identify moved subjects
        foreach ($existingSubjects as $subject) {
            if (isset($newSubjectMappings[$subject->id])) {
                $oldMapping = $subject->pivot;
                $newMapping = (object)$newSubjectMappings[$subject->id];
                
                if ($oldMapping->year != $newMapping->year || $oldMapping->semester != $newMapping->semester) {
                    CurriculumVersionService::createSnapshotOnUpdate(
                        $curriculum->id,
                        "Moved subject '{$subject->subject_name}' from Year {$oldMapping->year}, Sem {$oldMapping->semester} to Year {$newMapping->year}, Sem {$newMapping->semester}"
                    );
                }
            }
        }
    });

    session()->flash('success', 'Curriculum subjects have been saved successfully!');
    
    // Also trigger notification for AJAX requests
    if (request()->wantsJson()) {
        return response()->json([
            'message' => 'Curriculum saved successfully!', 
            'curriculumId' => $curriculum->id,
            'notification' => [
                'type' => 'success',
                'title' => 'Subjects Saved!',
                'message' => 'Curriculum subjects have been saved successfully!'
            ]
        ]);
    }
    
    return response()->json(['message' => 'Curriculum saved successfully!', 'curriculumId' => $curriculum->id]);
}

    /**
     * REMOVE a subject from a curriculum and log it to history.
     */
    public function removeSubject(Request $request)
    {
        $validated = $request->validate([
            'curriculumId' => 'required|exists:curriculums,id',
            'subjectId' => 'required|exists:subjects,id',
            'year' => 'required|integer',
            'semester' => 'required|integer',
        ]);

        try {
            $subjectName = null; // Initialize variable to store subject name
            
            DB::transaction(function () use ($validated, &$subjectName) {
                $curriculum = Curriculum::findOrFail($validated['curriculumId']);
                $subject = Subject::findOrFail($validated['subjectId']);
                
                // Store subject name for use outside transaction
                $subjectName = $subject->subject_name;

                // Detach the subject from the pivot table.
                $detached = $curriculum->subjects()
                           ->wherePivot('year', $validated['year'])
                           ->wherePivot('semester', $validated['semester'])
                           ->detach($validated['subjectId']);

                if ($detached == 0) {
                     throw new \Exception('Subject could not be found in the specified curriculum to remove.');
                }


                // Create version snapshot after removing subject
                CurriculumVersionService::createSnapshotOnSubjectRemove(
                    $validated['curriculumId'], 
                    $subject->subject_name
                );
            });

            session()->flash('success', 'Subject "' . $subjectName . '" has been removed from curriculum successfully!');
            
            // Also trigger notification for AJAX requests
            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Subject removed and recorded in history.',
                    'notification' => [
                        'type' => 'success',
                        'title' => 'Subject Removed!',
                        'message' => 'Subject "' . $subjectName . '" has been removed from curriculum successfully!'
                    ]
                ]);
            }
            
            return response()->json(['message' => 'Subject removed and recorded in history.']);

        } catch (\Exception $e) {
            Log::error('Error removing subject from curriculum: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while removing the subject.'], 500);
        }
    }

    public function getCurriculumDetailsForExport($id)
    {
        try {
            $curriculum = Curriculum::with('subjects.prerequisites')->findOrFail($id);
            return response()->json($curriculum);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'A database error occurred while fetching curriculum details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}