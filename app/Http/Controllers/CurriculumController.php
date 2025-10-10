<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use App\Models\Subject;
use App\Models\SubjectHistory;
use App\Services\CurriculumVersionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CurriculumController extends Controller
{
    /**
     * Retrieves all curriculums formatted for a dropdown selector.
     */
    public function index()
    {
        $curriculums = Curriculum::orderBy('year_level')->orderBy('curriculum')->get()->map(function ($curriculum) {
            return [
                'id' => $curriculum->id,
                'curriculum_name' => $curriculum->curriculum,
                'program_code' => $curriculum->program_code,
                'academic_year' => $curriculum->academic_year,
                'year_level' => $curriculum->year_level,
                'created_at' => $curriculum->created_at
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

        return response()->json(['message' => 'Curriculum updated successfully!', 'curriculum' => $curriculum]);
    }

    /**
     * Deletes a curriculum.
     */
    public function destroy($id)
    {
        $curriculum = Curriculum::findOrFail($id);
        $curriculum->delete();
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

            $removedSubjectCodes = SubjectHistory::where('curriculum_id', $id)
                                                  ->where('action', 'removed')
                                                  ->pluck('subject_code')
                                                  ->unique();

            return response()->json([
                'curriculum' => $curriculum,
                'allSubjects' => $allSubjects,
                'removedSubjectCodes' => $removedSubjectCodes,
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
        $newSubjectIds = collect();
        
        $curriculum->subjects()->detach(); // Clear existing subjects for a fresh save

        foreach ($validated['curriculumData'] as $data) {
            if (empty($data['subjects'])) {
                continue; 
            }
            
            foreach ($data['subjects'] as $subjectData) {
                $subject = Subject::where('subject_code', $subjectData['subject_code'])->first();

                if ($subject) {
                    $curriculum->subjects()->attach($subject->id, [
                        'year' => $data['year'],
                        'semester' => $data['semester'],
                    ]);
                    
                    $newSubjectIds->push($subject->id);
                    
                    // Create snapshot for newly added subjects
                    if (!$existingSubjects->has($subject->id)) {
                        CurriculumVersionService::createSnapshotOnSubjectAdd(
                            $validated['curriculumId'], 
                            $subject->subject_name
                        );
                        
                        // Log the addition for debugging
                        \Log::info("Created snapshot for subject addition: {$subject->subject_name} to curriculum {$validated['curriculumId']}");
                    }
                }
            }
        }

        // Create snapshots for removed subjects
        $removedSubjects = $existingSubjects->whereNotIn('id', $newSubjectIds);
        foreach ($removedSubjects as $removedSubject) {
            // Prepare removed subject data
            $removedSubjectData = [
                'subject_name' => $removedSubject->subject_name,
                'subject_code' => $removedSubject->subject_code,
                'subject_type' => $removedSubject->subject_type,
                'subject_unit' => $removedSubject->subject_unit,
                'year' => $removedSubject->pivot->year ?? 1,
                'semester' => $removedSubject->pivot->semester ?? 1,
            ];
            
            CurriculumVersionService::createSnapshotOnSubjectRemove(
                $validated['curriculumId'], 
                $removedSubject->subject_name,
                $removedSubjectData
            );
            
            // Log the removal for debugging
            \Log::info("Created snapshot for subject removal: {$removedSubject->subject_name} from curriculum {$validated['curriculumId']}");
        }

        // Create general update snapshot if no individual changes were tracked
        if ($removedSubjects->isEmpty() && $newSubjectIds->isEmpty()) {
            CurriculumVersionService::createSnapshotOnUpdate(
                $validated['curriculumId'], 
                'Curriculum subjects updated via subject mapping'
            );
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
            DB::transaction(function () use ($validated) {
                $curriculum = Curriculum::findOrFail($validated['curriculumId']);
                $subject = Subject::findOrFail($validated['subjectId']);

                // Detach the subject from the pivot table.
                $detached = $curriculum->subjects()
                           ->wherePivot('year', $validated['year'])
                           ->wherePivot('semester', $validated['semester'])
                           ->detach($validated['subjectId']);

                if ($detached == 0) {
                     throw new \Exception('Subject could not be found in the specified curriculum to remove.');
                }

                // Create a record in the subject history table
                SubjectHistory::create([
                    'curriculum_id' => $validated['curriculumId'],
                    'subject_id'    => $validated['subjectId'],
                    'academic_year' => $curriculum->academic_year,
                    'subject_name'  => $subject->subject_name,
                    'subject_code'  => $subject->subject_code,
                    'year'          => $validated['year'],
                    'semester'      => $validated['semester'],
                    'action'        => 'removed',
                ]);


                // Create version snapshot after removing subject
                CurriculumVersionService::createSnapshotOnSubjectRemove(
                    $validated['curriculumId'], 
                    $subject->subject_name
                );
            });

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