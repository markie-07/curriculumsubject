<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\SubjectHistory; // ADDED: For logging removed subjects
use Illuminate\Support\Facades\DB; // ADDED: For database transactions
use Carbon\Carbon; // ADDED: For handling dates

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

        // FIX: Explicitly create the curriculum to map the request field names to the database column names.
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
        
        // FIX: The update method also needs to map the request field names.
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
            $allSubjects = Subject::all(); // Always fetch all subjects

            // **NEW**: Get the codes of subjects that have been removed for this curriculum
            $removedSubjectCodes = SubjectHistory::where('curriculum_id', $id)
                                                 ->pluck('subject_code')
                                                 ->unique();

            return response()->json([
                'curriculum' => $curriculum,
                'allSubjects' => $allSubjects,
                'removedSubjectCodes' => $removedSubjectCodes, // **NEW**: Pass this to the frontend
            ]);
        } catch (\Exception $e) {
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
        $curriculum->subjects()->detach(); // Clear existing subjects for a fresh save

        foreach ($validated['curriculumData'] as $data) {
            if (empty($data['subjects'])) {
                continue; // Skip if a semester has no subjects
            }
            
            foreach ($data['subjects'] as $subjectData) {
                // Find the subject by its unique code
                $subject = Subject::where('subject_code', $subjectData['subject_code'])->first();

                // If the subject exists, attach it to the curriculum
                if ($subject) {
                    $curriculum->subjects()->attach($subject->id, [
                        'year' => $data['year'],
                        'semester' => $data['semester'],
                    ]);
                }
                // If it doesn't exist, you might want to log an error or handle it,
                // but for now, we'll just skip it to prevent crashes.
            }
        }

        return response()->json(['message' => 'Curriculum saved successfully!', 'curriculumId' => $curriculum->id]);
    }

    public function removeSubject(Request $request)
    {
        $validated = $request->validate([
            'curriculumId' => 'required|exists:curriculums,id',
            'subjectId'    => 'required|exists:subjects,id', // Validating based on subject ID
            'year'         => 'required|integer',
            'semester'     => 'required|integer',
        ]);

        try {
            // Use a database transaction to ensure data integrity
            DB::transaction(function () use ($validated) {
                $curriculum = Curriculum::findOrFail($validated['curriculumId']);
                $subject = Subject::findOrFail($validated['subjectId']);

                // Find the original pivot record to get its creation date
                $pivotRecord = DB::table('curriculum_subject')
                    ->where('curriculum_id', $validated['curriculumId'])
                    ->where('subject_id', $validated['subjectId'])
                    ->where('year', $validated['year'])
                    ->where('semester', $validated['semester'])
                    ->first();

                if (!$pivotRecord) {
                    // This case should ideally not happen if the frontend is correct
                    throw new \Exception('Subject not found in the specified semester for this curriculum.');
                }

                // Detach the subject from the pivot table based on all criteria
                $curriculum->subjects()->wherePivot('year', $validated['year'])->wherePivot('semester', $validated['semester'])->detach($validated['subjectId']);

                // Construct the academic year range for the history log
                $startYear = Carbon::parse($pivotRecord->created_at)->year;
                $endYear = Carbon::now()->year;
                $academicYearRange = ($startYear === $endYear) ? (string)$startYear : "{$startYear}-{$endYear}";

                // Create the history log entry
                SubjectHistory::create([
                    'curriculum_id' => $curriculum->id,
                    'subject_id'    => $subject->id,
                    'academic_year' => $academicYearRange,
                    'subject_name'  => $subject->subject_name,
                    'subject_code'  => $subject->subject_code,
                    'year'          => $validated['year'],
                    'semester'      => $validated['semester'],
                    'action'        => 'removed', // You can track different actions later
                ]);
            });

            return response()->json(['message' => 'Subject removed and history logged successfully.']);

        } catch (\Exception $e) {
            // Return a detailed error message for easier debugging
            return response()->json(['message' => 'Failed to remove subject: ' . $e->getMessage()], 500);
        }
    }

    public function getCurriculumDetailsForExport($id)
{
    try {
        // Eager load subjects and their prerequisites
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
