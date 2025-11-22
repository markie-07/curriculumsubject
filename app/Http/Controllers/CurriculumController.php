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
            ->orderByDesc('academic_year')
            ->get()
            ->map(function ($curriculum) {
                return [
                    'id' => $curriculum->id,
                    'curriculum_name' => $curriculum->curriculum,
                    'program_code' => $curriculum->program_code,
                    'academic_year' => $curriculum->academic_year,
                    'year_level' => $curriculum->year_level,
                    'compliance' => $curriculum->compliance,
                    'memorandum_year' => $curriculum->memorandum_year,
                    'memorandum_category' => $curriculum->memorandum_category,
                    'memorandum' => $curriculum->memorandum,
                    'semester_units' => $curriculum->semester_units,
                    'total_units' => $curriculum->total_units,
                    'version_status' => $curriculum->version_status,
                    'approval_status' => $curriculum->approval_status,
                    'created_at' => $curriculum->created_at,
                    'subjects_count' => $curriculum->subjects_count,
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
            'programCode' => 'required|string|max:255',
            'academicYear' => 'required|string|max:255',
            'yearLevel' => 'required|in:Senior High,College',
            'compliance' => 'nullable|string|in:CHED,DepEd',
            'memorandumYear' => 'nullable|string|max:4',
            'memorandumCategory' => 'nullable|string|max:255',
            'memorandum' => 'nullable|string',
            'semesterUnits' => 'nullable|array',
            'semesterUnits.*' => 'nullable|numeric|min:0',
            'totalUnits' => 'nullable|numeric|min:0',
        ]);

        // Check for existing curricula with the same name and year level
        $existingCurricula = Curriculum::where('curriculum', $validated['curriculum'])
            ->where('year_level', $validated['yearLevel'])
            ->get();

        // Mark all existing curricula with the same name as old version
        if ($existingCurricula->isNotEmpty()) {
            foreach ($existingCurricula as $existing) {
                $existing->update(['version_status' => 'old']);
            }
        }

        $curriculum = Curriculum::create([
            'curriculum' => $validated['curriculum'],
            'program_code' => $validated['programCode'],
            'academic_year' => $validated['academicYear'],
            'year_level' => $validated['yearLevel'],
            'compliance' => $validated['compliance'] ?? null,
            'memorandum_year' => $validated['memorandumYear'] ?? null,
            'memorandum_category' => $validated['memorandumCategory'] ?? null,
            'memorandum' => $validated['memorandum'] ?? null,
            'semester_units' => $validated['semesterUnits'] ?? null,
            'total_units' => $validated['totalUnits'] ?? null,
            'version_status' => 'new', // New curricula are marked as 'new'
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
            'programCode' => 'required|string|max:255',
            'academicYear' => 'required|string|max:255',
            'yearLevel' => 'required|in:Senior High,College',
            'compliance' => 'nullable|string|in:CHED,DepEd',
            'memorandumYear' => 'nullable|string|max:4',
            'memorandumCategory' => 'nullable|string|max:255',
            'memorandum' => 'nullable|string',
            'semesterUnits' => 'nullable|array',
            'semesterUnits.*' => 'nullable|numeric|min:0',
            'totalUnits' => 'nullable|numeric|min:0',
        ]);
        
        $curriculum->update([
            'curriculum' => $validated['curriculum'],
            'program_code' => $validated['programCode'],
            'academic_year' => $validated['academicYear'],
            'year_level' => $validated['yearLevel'],
            'compliance' => $validated['compliance'] ?? null,
            'memorandum_year' => $validated['memorandumYear'] ?? null,
            'memorandum_category' => $validated['memorandumCategory'] ?? null,
            'memorandum' => $validated['memorandum'] ?? null,
            'semester_units' => $validated['semesterUnits'] ?? null,
            'total_units' => $validated['totalUnits'] ?? null,
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

    // If curriculum was rejected, revert to processing on modification
    if ($curriculum->approval_status === 'rejected') {
        $curriculum->update(['approval_status' => 'processing']);
    }
    
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
                
                // If curriculum was rejected, revert to processing on modification
                if ($curriculum->approval_status === 'rejected') {
                    $curriculum->update(['approval_status' => 'processing']);
                }

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

    /**
     * Get subjects available for a specific curriculum (subjects linked to this curriculum)
     */
    public function getCurriculumSubjects($id)
    {
        try {
            $curriculum = Curriculum::findOrFail($id);
            
            // Get all subjects that are linked to this curriculum (available for this curriculum)
            // This includes subjects that were created with this curriculum selected
            // We get subjects that either have no year/semester assigned (available for mapping)
            // or subjects that are already mapped to this curriculum
            $subjects = Subject::whereHas('curriculums', function($query) use ($id) {
                $query->where('curriculum_id', $id);
            })->get()->map(function ($subject) {
                return [
                    'id' => $subject->id,
                    'subject_name' => $subject->subject_name,
                    'subject_code' => $subject->subject_code,
                    'subject_type' => $subject->subject_type,
                    'subject_unit' => $subject->subject_unit,
                    'contact_hours' => $subject->contact_hours,
                    'course_description' => $subject->course_description,
                    'created_at' => $subject->created_at,
                ];
            });

            // Debug logging
            \Log::info("Curriculum ID: $id");
            \Log::info("Found subjects count: " . $subjects->count());
            \Log::info("Subjects: " . $subjects->toJson());

            return response()->json($subjects);
        } catch (\Exception $e) {
            \Log::error("Error in getCurriculumSubjects: " . $e->getMessage());
            return response()->json([
                'message' => 'A database error occurred while fetching curriculum subjects.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add subjects to a curriculum's available subjects pool
     */
    public function addSubjectsToCurriculum(Request $request, $id)
    {
        $validated = $request->validate([
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);

        try {
            $curriculum = Curriculum::findOrFail($id);
            
            // If curriculum was rejected, revert to processing on modification
            if ($curriculum->approval_status === 'rejected') {
                $curriculum->update(['approval_status' => 'processing']);
            }
            
            // Get existing subject IDs for this curriculum
            $existingSubjectIds = $curriculum->subjects()->pluck('subjects.id')->toArray();
            
            // Filter out subjects that are already linked to this curriculum
            $newSubjectIds = array_diff($validated['subject_ids'], $existingSubjectIds);
            
            if (empty($newSubjectIds)) {
                return response()->json([
                    'message' => 'All selected subjects are already available for this curriculum.',
                    'added_count' => 0
                ]);
            }
            
            // Attach new subjects to curriculum without year/semester (making them available for mapping)
            foreach ($newSubjectIds as $subjectId) {
                $curriculum->subjects()->attach($subjectId, [
                    'year' => null,
                    'semester' => null,
                ]);
            }
            
            $addedCount = count($newSubjectIds);
            
            // Create database notification for admins
            if (Auth::check()) {
                Notification::createForAdmins(
                    'success',
                    'Subjects Added to Curriculum',
                    $addedCount . ' subject(s) added to curriculum "' . $curriculum->curriculum . '" by ' . Auth::user()->name,
                    ['curriculum_id' => $curriculum->id, 'action' => 'subjects_added', 'count' => $addedCount]
                );
            }
            
            return response()->json([
                'message' => $addedCount . ' subject(s) added to curriculum successfully!',
                'added_count' => $addedCount,
                'notification' => [
                    'type' => 'success',
                    'title' => 'Subjects Added!',
                    'message' => $addedCount . ' subject(s) have been added to the curriculum successfully!'
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error("Error in addSubjectsToCurriculum: " . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while adding subjects to curriculum.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a curriculum
     */
    public function approve($id)
    {
        try {
            $curriculum = Curriculum::findOrFail($id);
            $curriculum->update(['approval_status' => 'approved']);

            // Create database notification for admins
            if (Auth::check()) {
                Notification::createForAdmins(
                    'success',
                    'Curriculum Approved',
                    'Curriculum "' . $curriculum->curriculum . '" has been approved by ' . Auth::user()->name,
                    ['curriculum_id' => $curriculum->id, 'action' => 'approved']
                );
            }

            return response()->json([
                'message' => 'Curriculum approved successfully!',
                'curriculum' => $curriculum,
                'notification' => [
                    'type' => 'success',
                    'title' => 'Curriculum Approved!',
                    'message' => 'Curriculum "' . $curriculum->curriculum . '" has been approved successfully!'
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error("Error approving curriculum: " . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while approving the curriculum.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a curriculum
     */
    public function reject($id)
    {
        try {
            $curriculum = Curriculum::findOrFail($id);
            $curriculum->update(['approval_status' => 'rejected']);

            // Create database notification for admins
            if (Auth::check()) {
                Notification::createForAdmins(
                    'warning',
                    'Curriculum Rejected',
                    'Curriculum "' . $curriculum->curriculum . '" has been rejected by ' . Auth::user()->name,
                    ['curriculum_id' => $curriculum->id, 'action' => 'rejected']
                );
            }

            return response()->json([
                'message' => 'Curriculum rejected successfully!',
                'curriculum' => $curriculum,
                'notification' => [
                    'type' => 'warning',
                    'title' => 'Curriculum Rejected!',
                    'message' => 'Curriculum "' . $curriculum->curriculum . '" has been rejected.'
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error("Error rejecting curriculum: " . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while rejecting the curriculum.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}