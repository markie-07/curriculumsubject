<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curriculum;
use App\Models\Prerequisite;

class PrerequisiteController extends Controller
{
    /**
     * Display the prerequisite management page.
     */
    public function index()
    {
        $curriculums = Curriculum::orderBy('curriculum')->get();
        return view('pre_requisite', compact('curriculums'));
    }

    /**
     * Fetch subjects and existing prerequisites for a given curriculum.
     * This will be called by our JavaScript.
     */
    public function fetchData(Curriculum $curriculum)
    {
        // Eager load subjects to improve performance
        $curriculum->load('subjects');

        $subjects = $curriculum->subjects->sortBy('subject_code')->values();

        $prerequisites = Prerequisite::where('curriculum_id', $curriculum->id)
            ->get()
            ->groupBy('subject_code'); // Group by the subject that HAS prerequisites

        return response()->json([
            'subjects' => $subjects,
            'prerequisites' => $prerequisites,
        ]);
    }

    /**
     * Store the prerequisite relationships for a subject.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'curriculum_id' => 'required|exists:curriculums,id',
            'subject_code' => 'required|string',
            'prerequisite_codes' => 'nullable|array',
            'prerequisite_codes.*' => 'string', // Ensure all items in the array are strings
        ]);

        // First, delete all existing prerequisites for this subject to avoid duplicates
        Prerequisite::where('curriculum_id', $validated['curriculum_id'])
            ->where('subject_code', $validated['subject_code'])
            ->delete();

        // Now, add the new prerequisites from the form submission
        if (!empty($validated['prerequisite_codes'])) {
            foreach ($validated['prerequisite_codes'] as $prereqCode) {
                Prerequisite::create([
                    'curriculum_id' => $validated['curriculum_id'],
                    'subject_code' => $validated['subject_code'],
                    'prerequisite_subject_code' => $prereqCode,
                ]);
            }
        }

        return response()->json(['message' => 'Prerequisites saved successfully!']);
    }
}
