<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use App\Models\Subject;
use App\Models\SubjectHistory;
use Illuminate\Http\Request; // Import the Request class
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;
use App\Services\CurriculumVersionService;

class SubjectHistoryController extends Controller
{
    /**
     * Display a listing of the subject history.
     */
    public function index(Request $request) // Add Request parameter to handle filtering
    {
        // Fetch all curriculums to populate the filter dropdown.
        $curriculums = Curriculum::orderBy('curriculum')->get();

        // Start building the query to get history records.
        $historyQuery = SubjectHistory::with('curriculum')->orderBy('created_at', 'desc');

        // If a specific curriculum is requested via the dropdown, filter the query.
        if ($request->filled('curriculum_id')) {
            $historyQuery->where('curriculum_id', $request->curriculum_id);
        }

        // Execute the query to get the final list of history records.
        $history = $historyQuery->get();

        // Pass the filtered history and the full list of curriculums to the view.
        return view('subject_history', [
            'history'     => $history,
            'curriculums' => $curriculums
        ]);
    }

    /**
     * Retrieve a subject from history and add it back to the curriculum.
     */
    public function retrieve($historyId)
    {
        try {
            DB::transaction(function () use ($historyId) {
                $historyRecord = SubjectHistory::findOrFail($historyId);

                $curriculum = Curriculum::find($historyRecord->curriculum_id);
                $subject = Subject::find($historyRecord->subject_id);

                if (!$curriculum || !$subject) {
                    throw new \Exception("Original Curriculum or Subject no longer exists.");
                }

                // Re-attach the subject to the curriculum_subject pivot table
                $curriculum->subjects()->attach($subject->id, [
                    'year'       => $historyRecord->year,
                    'semester'   => $historyRecord->semester,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Create version snapshot for subject retrieval
                CurriculumVersionService::createSnapshotOnSubjectRetrieve(
                    $historyRecord->curriculum_id,
                    $historyRecord->subject_name
                );

                // Create notification for subject retrieval
                NotificationService::subjectRetrieved(
                    $historyRecord->subject_name, 
                    $curriculum->curriculum, 
                    Auth::user()->name
                );

                // Delete the history record since it has been retrieved
                $historyRecord->delete();
            });

            return response()->json(['message' => 'Subject retrieved successfully.']);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve subject: ' . $e->getMessage()], 500);
        }
    }
}
