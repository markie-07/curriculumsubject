<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use App\Models\ExportHistory;
use Illuminate\Http\Request;
use PDF;

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

    /**
     * Export the curriculum data as a PDF.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function exportPdf($id)
    {
        $curriculum = Curriculum::with([
            'subjects' => function ($query) {
                // Order subjects by year and then by semester for a structured layout
                $query->orderBy('pivot_year', 'asc')->orderBy('pivot_semester', 'asc');
            }, 
            'subjects.prerequisites', 
            'subjects.grades' // Eager load grades for each subject
        ])->findOrFail($id);
    
        // Safeguard against null relationships.
        $curriculum->subjects->each(function ($subject) {
            if (is_null($subject->prerequisites)) {
                $subject->setRelation('prerequisites', collect());
            }
        });

        $pdf = PDF::loadView('curriculum_pdf', compact('curriculum'));
        
        // Sanitize the curriculum name to create a valid filename
        $fileName = preg_replace('/[^A-Za-z0-9\-]/', '_', $curriculum->program_code);
        
        return $pdf->download($fileName . '_curriculum.pdf');
    }
}

