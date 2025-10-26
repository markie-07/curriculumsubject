<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\Curriculum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    /**
     * Display the grade setup page.
     * This method loads the main view and is called by your web.php route.
     */
    public function setup()
    {
        // Get subjects that already have a grade setup to display in the Grade History
        $subjectsWithGrades = Grade::with('subject')->get()->pluck('subject')->filter();

        return view('grade_setup', ['subjects' => $subjectsWithGrades]);
    }

    /**
     * Get the grade setup for a specific subject ID.
     * This is used by the frontend JavaScript to fetch data.
     */
    public function show($id)
    {
        // We now fetch the grade setup using the subject_id
        $grade = Grade::with('subject')->where('subject_id', $id)->first();

        // If no setup exists for a subject, return null so the frontend can use a default
        if (!$grade) {
            return response()->json(['components' => null]);
        }
        return response()->json($grade);
    }

    /**
     * Store or update a grade setup.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'components' => 'required|array', // Ensure 'components' is a valid array/object
        ]);

        // Find or create the grade setup for the given subject
        $grade = Grade::updateOrCreate(
            ['subject_id' => $validated['subject_id']],
            ['components' => $validated['components']]
        );
        
        // Return the subject details so the JavaScript can add it to the "Grade History" list.
        $subject = Subject::find($validated['subject_id']);

        // Flash success message for session-based requests
        session()->flash('success', 'Grade scheme for "' . $subject->subject_name . '" has been saved successfully!');
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Grade scheme saved successfully!',
                'subject' => $subject,
                'notification' => [
                    'type' => 'success',
                    'title' => 'Grade Scheme Saved!',
                    'message' => 'Grade scheme for "' . $subject->subject_name . '" has been saved successfully!'
                ]
            ], 201);
        }

        return response()->json([
            'success' => true,
            'message' => 'Grade scheme saved successfully!',
            'subject' => $subject // Send back the subject data
        ], 201);
    }

    /**
     * Store curriculum-based grade schemes
     */
    public function storeCurriculumGrades(Request $request)
    {
        $validated = $request->validate([
            'curriculum_id' => 'required|exists:curriculums,id',
            'course_type' => 'required|in:minor,major',
            'subjects' => 'required|array',
            'subjects.*.subject_id' => 'required|exists:subjects,id',
            'subjects.*.components' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            $curriculum = Curriculum::findOrFail($validated['curriculum_id']);
            $savedSubjects = [];

            foreach ($validated['subjects'] as $subjectData) {
                $grade = Grade::updateOrCreate(
                    ['subject_id' => $subjectData['subject_id']],
                    [
                        'components' => $subjectData['components'],
                        'curriculum_id' => $validated['curriculum_id'],
                        'course_type' => $validated['course_type']
                    ]
                );

                $subject = Subject::find($subjectData['subject_id']);
                $savedSubjects[] = $subject;
            }

            DB::commit();

            // Flash success message
            $courseTypeText = $validated['course_type'] === 'minor' ? 'minor courses' : 'major course';
            session()->flash('success', "Grade schemes for {$courseTypeText} in curriculum \"{$curriculum->curriculum}\" have been saved successfully!");

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Curriculum grade schemes saved successfully!',
                    'curriculum' => [
                        'id' => $curriculum->id,
                        'curriculum_name' => $curriculum->curriculum,
                        'program_code' => $curriculum->program_code,
                        'academic_year' => $curriculum->academic_year
                    ],
                    'subjects' => $savedSubjects,
                    'notification' => [
                        'type' => 'success',
                        'title' => 'Grade Schemes Saved!',
                        'message' => "Grade schemes for {$courseTypeText} have been saved successfully!"
                    ]
                ], 201);
            }

            return response()->json([
                'success' => true,
                'message' => 'Curriculum grade schemes saved successfully!',
                'curriculum' => $curriculum
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to save curriculum grade schemes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all curriculums that have grade schemes set up
     */
    public function getAllCurriculumGrades()
    {
        try {
            // Get all curriculums that have at least one subject with grades
            $curriculumsWithGrades = Curriculum::whereHas('subjects.grade')
                ->with(['subjects' => function ($query) {
                    $query->whereHas('grade');
                }])
                ->get()
                ->map(function ($curriculum) {
                    return [
                        'id' => $curriculum->id,
                        'curriculum_name' => $curriculum->curriculum,
                        'program_code' => $curriculum->program_code,
                        'academic_year' => $curriculum->academic_year,
                        'subjects_with_grades_count' => $curriculum->subjects->count()
                    ];
                });

            return response()->json($curriculumsWithGrades);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch curriculum grades: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get curriculum grade schemes with subjects
     */
    public function getCurriculumGrades($curriculumId)
    {
        try {
            $curriculum = Curriculum::with(['subjects' => function ($query) {
                $query->with('grade');
            }])->findOrFail($curriculumId);

            $subjects = $curriculum->subjects->map(function ($subject) {
                return [
                    'id' => $subject->id,
                    'subject_name' => $subject->subject_name,
                    'subject_code' => $subject->subject_code,
                    'subject_type' => $subject->subject_type,
                    'subject_unit' => $subject->subject_unit,
                    'has_grades' => $subject->grade ? true : false,
                    'grade_components' => $subject->grade ? $subject->grade->components : null,
                ];
            });

            return response()->json([
                'curriculum' => [
                    'id' => $curriculum->id,
                    'curriculum_name' => $curriculum->curriculum,
                    'program_code' => $curriculum->program_code,
                    'academic_year' => $curriculum->academic_year
                ],
                'subjects' => $subjects
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch curriculum grade details: ' . $e->getMessage()
            ], 500);
        }
    }
}