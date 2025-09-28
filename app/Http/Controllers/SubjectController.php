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
            'subjectName' => 'required|string|max:255',
            'subjectCode' => 'required|string|max:255|unique:subjects,subject_code',
            'subjectType' => 'required|string|in:Major,Minor,Elective',
            'subjectUnit' => 'required|integer',
            'lessons' => 'nullable|array',
        ]);

        $subject = Subject::create([
            'subject_name' => $validated['subjectName'],
            'subject_code' => $validated['subjectCode'],
            'subject_type' => $validated['subjectType'],
            'subject_unit' => $validated['subjectUnit'],
            'lessons' => $validated['lessons'],
        ]);

        return response()->json([
            'message' => 'Subject created successfully!',
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

    /**
     * Updates an existing subject.
     */
    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $validated = $request->validate([
            'subjectName' => 'required|string|max:255',
            // Allow the same subject code for the current subject being updated
            'subjectCode' => 'required|string|max:255|unique:subjects,subject_code,' . $subject->id,
            'subjectType' => 'required|string|in:Major,Minor,Elective',
            'subjectUnit' => 'required|integer',
            'lessons' => 'nullable|array',
        ]);

        $subject->update([
            'subject_name' => $validated['subjectName'],
            'subject_code' => $validated['subjectCode'],
            'subject_type' => $validated['subjectType'],
            'subject_unit' => $validated['subjectUnit'],
            'lessons' => $validated['lessons'],
        ]);

        return response()->json([
            'message' => 'Subject updated successfully!',
            'subject' => $subject,
        ]);
    }
}