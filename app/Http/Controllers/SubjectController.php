<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

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

        $subject->update($updateData);

        return response()->json([
            'message' => 'Subject updated successfully!',
            'subject' => $subject,
        ], 200);
    }
}