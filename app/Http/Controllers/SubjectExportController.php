<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Mpdf\Mpdf;
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
        
        // Find the curriculum this subject belongs to (get the first one if multiple)
        $curriculum = $subject->curriculums()->first();
        
        // If we have a curriculum, get prerequisite data
        $prerequisiteData = [];
        if ($curriculum) {
            // Fetch all prerequisite relationships for the current curriculum.
            $allPrerequisites = \App\Models\Prerequisite::where('curriculum_id', $curriculum->id)->get();

            // MAP 1: PARENTS (for Credit Prerequisites) - What subjects are required before this one
            $subjectToParentsMap = $allPrerequisites->groupBy('subject_code')->map(function ($item) {
                return $item->pluck('prerequisite_subject_code')->all();
            })->all();

            // MAP 2: CHILDREN (for Pre-requisite to) - What subjects require this one as prerequisite
            $subjectToChildrenMap = $allPrerequisites->groupBy('prerequisite_subject_code')->map(function ($item) {
                return $item->pluck('subject_code')->all();
            })->all();
            
            $prerequisiteData = [
                'subjectToParentsMap' => $subjectToParentsMap,
                'subjectToChildrenMap' => $subjectToChildrenMap
            ];
        }

        // Generate HTML from the view
        $html = view('subject_pdf', [
            'subject' => $subject,
            'curriculum' => $curriculum,
            'prerequisiteData' => $prerequisiteData
        ])->render();
        
        // Create mPDF instance
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
        ]);
        
        // Write HTML to PDF
        $mpdf->WriteHTML($html);
        
        // Generate a filename based on the subject code and return the PDF for download.
        $fileName = preg_replace('/[^A-Za-z0-9\-]/', '_', $subject->subject_code);
        
        // Output PDF for download
        return response($mpdf->Output($fileName . '_details.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '_details.pdf"');
    }
}
