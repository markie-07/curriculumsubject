<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SubjectExportController extends Controller
{
    /**
     * Export subject details, including its grading system, to a PDF file.
     *
     * @param int $subjectId The ID of the subject to export.
     * @return \Illuminate\Http\Response
     */
    public function exportPdf($subjectId)
    {
        // Use findOrFail to automatically handle cases where the subject is not found.
        // Eager load the 'grades' relationship to avoid extra database queries.
        $subject = Subject::with('grades')->findOrFail($subjectId);

        // Load the view 'subject_pdf' and pass the subject data to it.
        $pdf = PDF::loadView('subject_pdf', ['subject' => $subject]);

        // Generate a filename based on the subject code and return the PDF for download.
        return $pdf->download($subject->subject_code . '_details.pdf');
    }
}
