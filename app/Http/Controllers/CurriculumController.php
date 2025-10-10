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