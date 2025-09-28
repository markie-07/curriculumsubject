<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Subject; // REMOVED the extra period from this line
use Illuminate\Http\Request;

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

        return response()->json([
            'success' => true,
            'message' => 'Grade scheme saved successfully!',
            'subject' => $subject // Send back the subject data
        ], 201);
    }
}