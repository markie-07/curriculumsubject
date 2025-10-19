<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\SubjectVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function index()
    {
        return response()->json(Subject::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_title' => 'required|string|max:255',
            'subject_code' => 'required|string|max:255|unique:subjects,subject_code',
            'subject_unit' => 'required|integer',
            'subject_type' => 'required|string|in:Major,Minor,Elective,General',
            'lessons' => 'nullable|array',
            'contact_hours' => 'nullable|integer',
            'prerequisites' => 'nullable|string',
            'pre_requisite_to' => 'nullable|string',
            'course_description' => 'nullable|string',
            'program_mapping_grid' => 'nullable|array',
            'course_mapping_grid' => 'nullable|array',
            'pilo_outcomes' => 'nullable|string',
            'cilo_outcomes' => 'nullable|string',
            'learning_outcomes' => 'nullable|string',
            'basic_readings' => 'nullable|string',
            'extended_readings' => 'nullable|string',
            'course_assessment' => 'nullable|string',
            'committee_members' => 'nullable|string',
            'consultation_schedule' => 'nullable|string',
            'prepared_by' => 'nullable|string',
            'reviewed_by' => 'nullable|string',
            'approved_by' => 'nullable|string',
        ]);

        $subject = Subject::create([
            'subject_name' => $validated['course_title'],
            'subject_code' => $validated['subject_code'],
            'subject_type' => $validated['subject_type'],
            'subject_unit' => $validated['subject_unit'],
            'lessons' => $validated['lessons'] ?? null,
            'contact_hours' => $validated['contact_hours'] ?? null,
            'prerequisites' => $validated['prerequisites'] ?? null,
            'pre_requisite_to' => $validated['pre_requisite_to'] ?? null,
            'course_description' => $validated['course_description'] ?? null,
            'program_mapping_grid' => $validated['program_mapping_grid'] ?? null,
            'course_mapping_grid' => $validated['course_mapping_grid'] ?? null,
            'pilo_outcomes' => $validated['pilo_outcomes'] ?? null,
            'cilo_outcomes' => $validated['cilo_outcomes'] ?? null,
            'learning_outcomes' => $validated['learning_outcomes'] ?? null,
            'basic_readings' => $validated['basic_readings'] ?? null,
            'extended_readings' => $validated['extended_readings'] ?? null,
            'course_assessment' => $validated['course_assessment'] ?? null,
            'committee_members' => $validated['committee_members'] ?? null,
            'consultation_schedule' => $validated['consultation_schedule'] ?? null,
            'prepared_by' => $validated['prepared_by'] ?? null,
            'reviewed_by' => $validated['reviewed_by'] ?? null,
            'approved_by' => $validated['approved_by'] ?? null,
        ]);

        // Flash success message for session-based requests
        session()->flash('success', 'Subject "' . $subject->subject_name . '" has been created successfully!');
        
        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Subject created successfully! Ready for mapping.',
                'subject' => $subject,
                'notification' => [
                    'type' => 'success',
                    'title' => 'Subject Created!',
                    'message' => 'Subject "' . $subject->subject_name . '" has been created successfully!'
                ]
            ], 201);
        }
        
        return response()->json([
            'message' => 'Subject created successfully! Ready for mapping.',
            'subject' => $subject,
        ], 201);
    }

    public function show($id)
    {
        return response()->json(Subject::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $validated = $request->validate([
            'course_title' => 'required|string|max:255',
            'subject_code' => 'required|string|max:255|unique:subjects,subject_code,' . $subject->id,
            'subject_unit' => 'required|integer',
            'subject_type' => 'required|string|in:Major,Minor,Elective,General',
            'lessons' => 'nullable|array',
            'contact_hours' => 'nullable|integer',
            'prerequisites' => 'nullable|string',
            'pre_requisite_to' => 'nullable|string',
            'course_description' => 'nullable|string',
            'program_mapping_grid' => 'nullable|array',
            'course_mapping_grid' => 'nullable|array',
            'pilo_outcomes' => 'nullable|string',
            'cilo_outcomes' => 'nullable|string',
            'learning_outcomes' => 'nullable|string',
            'basic_readings' => 'nullable|string',
            'extended_readings' => 'nullable|string',
            'course_assessment' => 'nullable|string',
            'committee_members' => 'nullable|string',
            'consultation_schedule' => 'nullable|string',
            'prepared_by' => 'nullable|string',
            'reviewed_by' => 'nullable|string',
            'approved_by' => 'nullable|string',
        ]);

        $updateData = [
            'subject_name' => $validated['course_title'],
            'subject_code' => $validated['subject_code'],
            'subject_type' => $validated['subject_type'],
            'subject_unit' => $validated['subject_unit'],
            'lessons' => $validated['lessons'] ?? null,
            'contact_hours' => $validated['contact_hours'] ?? null,
            'prerequisites' => $validated['prerequisites'] ?? null,
            'pre_requisite_to' => $validated['pre_requisite_to'] ?? null,
            'course_description' => $validated['course_description'] ?? null,
            'program_mapping_grid' => $validated['program_mapping_grid'] ?? null,
            'course_mapping_grid' => $validated['course_mapping_grid'] ?? null,
            'pilo_outcomes' => $validated['pilo_outcomes'] ?? null,
            'cilo_outcomes' => $validated['cilo_outcomes'] ?? null,
            'learning_outcomes' => $validated['learning_outcomes'] ?? null,
            'basic_readings' => $validated['basic_readings'] ?? null,
            'extended_readings' => $validated['extended_readings'] ?? null,
            'course_assessment' => $validated['course_assessment'] ?? null,
            'committee_members' => $validated['committee_members'] ?? null,
            'consultation_schedule' => $validated['consultation_schedule'] ?? null,
            'prepared_by' => $validated['prepared_by'] ?? null,
            'reviewed_by' => $validated['reviewed_by'] ?? null,
            'approved_by' => $validated['approved_by'] ?? null,
        ];

        // Use database transaction to ensure data consistency
        DB::transaction(function () use ($subject, $updateData, $request) {
            // Get the next version number
            $nextVersionNumber = SubjectVersion::where('subject_id', $subject->id)->max('version_number') + 1;
            
            // Save the current state as a version before updating
            SubjectVersion::createFromSubject(
                $subject, 
                $nextVersionNumber,
                $request->input('change_reason', 'Subject updated'),
                $request->input('changed_by', 'System')
            );
            
            // Update the subject with new data
            $subject->update($updateData);
        });

        // Flash success message for session-based requests
        session()->flash('success', 'Subject "' . $subject->subject_name . '" has been updated successfully!');
        
        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Subject updated successfully!',
                'subject' => $subject,
                'notification' => [
                    'type' => 'success',
                    'title' => 'Subject Updated!',
                    'message' => 'Subject "' . $subject->subject_name . '" has been updated successfully!'
                ]
            ], 200);
        }
        
        return response()->json([
            'message' => 'Subject updated successfully!',
            'subject' => $subject,
        ], 200);
    }
    
    /**
     * Remove the specified subject from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        try {
            // Store subject name before deletion
            $subjectName = $subject->subject_name;
            
            // The subject is already loaded thanks to route model binding.
            $subject->delete();
            
            // Flash success message for session-based requests
            session()->flash('success', 'Subject "' . $subjectName . '" has been deleted successfully!');
            
            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Subject deleted successfully.',
                    'notification' => [
                        'type' => 'success',
                        'title' => 'Subject Deleted!',
                        'message' => 'Subject "' . $subjectName . '" has been deleted successfully!'
                    ]
                ], 200);
            }
            
            return response()->json(['message' => 'Subject deleted successfully.'], 200);

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error deleting subject: '.$e->getMessage());

            // Return a generic error response
            return response()->json(['message' => 'An error occurred while deleting the subject.'], 500);
        }
    }

    /**
     * Get version history for a subject (current and previous version)
     */
    public function getVersionHistory($id)
    {
        try {
            $currentSubject = Subject::findOrFail($id);
            
            // Get all versions from subject_versions table, ordered by version number descending
            $allVersions = SubjectVersion::where('subject_id', $id)
                ->orderBy('version_number', 'desc')
                ->get();
            
            $previousVersions = [];
            
            if ($allVersions->count() > 0) {
                // Convert all versions to subject-like format for consistency
                foreach ($allVersions as $version) {
                    $previousVersions[] = [
                        'id' => $version->id,
                        'subject_name' => $version->subject_name,
                        'subject_code' => $version->subject_code,
                        'subject_type' => $version->subject_type,
                        'subject_unit' => $version->subject_unit,
                        'units' => $version->subject_unit, // For compatibility with frontend
                        'contact_hours' => $version->contact_hours,
                        'prerequisites' => $version->prerequisites,
                        'pre_requisite_to' => $version->pre_requisite_to,
                        'course_description' => $version->course_description,
                        'program_mapping_grid' => $version->program_mapping_grid,
                        'course_mapping_grid' => $version->course_mapping_grid,
                        'pilo_outcomes' => $version->pilo_outcomes,
                        'cilo_outcomes' => $version->cilo_outcomes,
                        'learning_outcomes' => $version->learning_outcomes,
                        'lessons' => $version->lessons,
                        'basic_readings' => $version->basic_readings,
                        'extended_readings' => $version->extended_readings,
                        'course_assessment' => $version->course_assessment,
                        'committee_members' => $version->committee_members,
                        'consultation_schedule' => $version->consultation_schedule,
                        'prepared_by' => $version->prepared_by,
                        'reviewed_by' => $version->reviewed_by,
                        'approved_by' => $version->approved_by,
                        'created_at' => $version->created_at,
                        'updated_at' => $version->updated_at,
                        'version_number' => $version->version_number,
                        'change_reason' => $version->change_reason,
                        'changed_by' => $version->changed_by,
                    ];
                }
                
                return response()->json([
                    'hasOldVersion' => true,
                    'currentVersion' => $currentSubject,
                    'previousVersions' => $previousVersions,
                    'totalVersions' => $allVersions->count()
                ]);
            } else {
                return response()->json([
                    'hasOldVersion' => false,
                    'currentVersion' => $currentSubject,
                    'previousVersions' => [],
                    'totalVersions' => 0
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Error fetching subject version history: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while fetching version history.'], 500);
        }
    }
}