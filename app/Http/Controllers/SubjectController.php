<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Retrieves all subjects.
     */
    public function index()
    {
        return response()->json(Subject::all());
    }

    /**
     * Stores a new subject.
     */
    public function store(Request $request)
    {
            $validated = $request->validate([
            // Map the frontend names to the expected Subject model attributes
            'course_title' => 'required|string|max:255',   // Maps to subject_name
            'course_code' => 'required|string|max:255|unique:subjects,subject_code', // Maps to subject_code
            'credit_units' => 'required|integer',        // Maps to subject_unit
            'subject_type' => 'required|string|in:Major,Minor,Elective,General', // Must be explicitly selected/sent
            'lessons' => 'nullable|array', // The compiled JSON array of the weekly plan
        ]);

            $subject = Subject::create([
            'subject_name' => $validated['course_title'],
            'subject_code' => $validated['course_code'],
            'subject_type' => $validated['subject_type'], // Use the value sent from the form
            'subject_unit' => $validated['credit_units'],
            'lessons' => $validated['lessons'] ?? [], // Store the entire compiled lessons object/array
        ]);

        return response()->json([
            'message' => 'Subject created successfully! Ready for mapping.',
            'subject' => $subject,
        ], 201);
    }
    
    
    /**
     * Retrieves a single subject.
     */
    public function show($id)
    {
        return response()->json(Subject::findOrFail($id));
    }
}