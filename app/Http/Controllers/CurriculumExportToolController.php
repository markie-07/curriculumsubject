<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use App\Models\ExportHistory;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class CurriculumExportToolController extends Controller
{
    /**
     * Display the export tool page with existing curriculums and history.
     */
    public function index()
    {
        $curriculums = Curriculum::orderBy('curriculum')->get();
        $exportHistories = ExportHistory::with('curriculum')->latest()->get();

        // Log page view activity for employees only
        if (auth()->user() && auth()->user()->isEmployee()) {
            ActivityLogService::logPageView('Curriculum Export Tool');
            auth()->user()->updateLastActivity();
        }

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

        // Log export activity for employees only
        if (auth()->user() && auth()->user()->isEmployee()) {
            $curriculum = Curriculum::find($validated['curriculum_id']);
            ActivityLogService::logExport(
                'curriculum_export',
                $validated['file_name'],
                [
                    'curriculum_id' => $validated['curriculum_id'],
                    'curriculum_name' => $curriculum->curriculum ?? 'Unknown',
                    'format' => $validated['format'],
                    'export_history_id' => $exportHistory->id,
                ]
            );
            auth()->user()->updateLastActivity();
        }

        // Return the new history item with its related curriculum info
        return response()->json($exportHistory->load('curriculum'));
    }

    /**
     * Export the curriculum data as a PDF.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf($id, Request $request)
    {
        // Get course type filters from request
        $courseTypes = $request->get('course_types', []);
        
        $curriculum = Curriculum::with([
            'subjects' => function ($query) use ($courseTypes) {
                // Order subjects by year and then by semester for a structured layout
                $query->orderBy('pivot_year', 'asc')->orderBy('pivot_semester', 'asc');
                
                // Apply course type filtering if specified
                if (!empty($courseTypes)) {
                    $query->whereIn('subject_type', $courseTypes);
                }
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

        // Generate HTML from the view
        $html = view('curriculum_pdf', compact('curriculum', 'courseTypes'))->render();
        
        // Create mPDF instance
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'margin_header' => 9,
            'margin_footer' => 9,
        ]);
        
        // Write HTML to PDF
        $mpdf->WriteHTML($html);
        
        // Sanitize the curriculum name to create a valid filename
        $fileName = preg_replace('/[^A-Za-z0-9\-]/', '_', $curriculum->program_code);
        
        // Output PDF for download
        return response($mpdf->Output($fileName . '_curriculum.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '_curriculum.pdf"');
    }
}

