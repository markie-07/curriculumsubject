<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use Barryvdh\DomPDF\Facade\Pdf;

class SubjectExportController extends Controller
{
    public function exportPdf($subjectId)
    {
        $subject = Subject::find($subjectId);

        if (!$subject) {
            return redirect()->back()->with('error', 'Subject not found.');
        }

        // The view name should be 'subject_pdf'
        $pdf = PDF::loadView('subject_pdf', ['subject' => $subject]);

        return $pdf->download($subject->subject_code . '_details.pdf');
    }
}