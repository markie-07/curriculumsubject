<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Equivalency;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class EquivalencyToolController extends Controller
{
    /**
     * Display the equivalency tool view with all subjects and existing equivalencies.
     */
    public function index(): View
    {
        // Fetch all subjects to populate the dropdown
        $subjects = Subject::orderBy('subject_code')->get();
        
        // Fetch all existing equivalencies, eager load the related subject, and order by the newest first
        $equivalencies = Equivalency::with('equivalentSubject')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('equivalency_tool', compact('subjects', 'equivalencies'));
    }

    /**
     * Store a newly created equivalency in the database.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'source_subject_name' => 'required|string|max:255',
            'equivalent_subject_id' => 'required|exists:subjects,id',
        ]);

        $equivalency = Equivalency::create($validated);

        // Return the new record with its relationship loaded so the frontend can display it
        return response()->json($equivalency->load('equivalentSubject'));
    }

    /**
     * Update the specified equivalency in the database.
     */
    public function update(Request $request, Equivalency $equivalency): JsonResponse
    {
        $validated = $request->validate([
            'source_subject_name' => 'required|string|max:255',
            'equivalent_subject_id' => 'required|exists:subjects,id',
        ]);

        $equivalency->update($validated);

        // Return the updated record with its relationship loaded
        return response()->json($equivalency->load('equivalentSubject'));
    }

    /**
     * Remove the specified equivalency from the database.
     */
    public function destroy(Equivalency $equivalency): JsonResponse
    {
        $equivalency->delete();
        
        // Return a success message
        return response()->json(['message' => 'Equivalency deleted successfully.']);
    }
}