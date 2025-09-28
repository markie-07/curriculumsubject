<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use App\Models\ExportHistory;
use Illuminate\Http\Request;

class CurriculumExportToolController extends Controller
{
    /**
     * Display the export tool page with existing curriculums and history.
     */
    public function index()
    {
        $curriculums = Curriculum::orderBy('curriculum')->get();
        $exportHistories = ExportHistory::with('curriculum')->latest()->get();

        return view('curriculum_export_tool', compact('curriculums', 'exportHistories'));
    }

    /**
     * Store a new export history record in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'curriculum_id' => 'required|exists:curriculums,id',
            'file_name' => 'required|string|max:255',
            'format' => 'required|string|max:255',
        ]);

        $exportHistory = ExportHistory::create($validated);

        // Return the new history item with its related curriculum info
        return response()->json($exportHistory->load('curriculum'));
    }
}