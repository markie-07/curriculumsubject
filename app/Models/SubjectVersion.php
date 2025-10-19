<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'version_number',
        'subject_name',
        'subject_code',
        'subject_type',
        'subject_unit',
        'contact_hours',
        'prerequisites',
        'pre_requisite_to',
        'course_description',
        'program_mapping_grid',
        'course_mapping_grid',
        'pilo_outcomes',
        'cilo_outcomes',
        'learning_outcomes',
        'lessons',
        'basic_readings',
        'extended_readings',
        'course_assessment',
        'committee_members',
        'consultation_schedule',
        'prepared_by',
        'reviewed_by',
        'approved_by',
        'change_reason',
        'changed_by',
    ];

    protected $casts = [
        'program_mapping_grid' => 'array',
        'course_mapping_grid' => 'array',
        'lessons' => 'array',
    ];

    /**
     * Get the subject that owns this version
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Create a version snapshot from a subject
     */
    public static function createFromSubject(Subject $subject, $versionNumber, $changeReason = null, $changedBy = null)
    {
        return self::create([
            'subject_id' => $subject->id,
            'version_number' => $versionNumber,
            'subject_name' => $subject->subject_name,
            'subject_code' => $subject->subject_code,
            'subject_type' => $subject->subject_type,
            'subject_unit' => $subject->subject_unit,
            'contact_hours' => $subject->contact_hours,
            'prerequisites' => $subject->prerequisites,
            'pre_requisite_to' => $subject->pre_requisite_to,
            'course_description' => $subject->course_description,
            'program_mapping_grid' => $subject->program_mapping_grid,
            'course_mapping_grid' => $subject->course_mapping_grid,
            'pilo_outcomes' => $subject->pilo_outcomes,
            'cilo_outcomes' => $subject->cilo_outcomes,
            'learning_outcomes' => $subject->learning_outcomes,
            'lessons' => $subject->lessons,
            'basic_readings' => $subject->basic_readings,
            'extended_readings' => $subject->extended_readings,
            'course_assessment' => $subject->course_assessment,
            'committee_members' => $subject->committee_members,
            'consultation_schedule' => $subject->consultation_schedule,
            'prepared_by' => $subject->prepared_by,
            'reviewed_by' => $subject->reviewed_by,
            'approved_by' => $subject->approved_by,
            'change_reason' => $changeReason,
            'changed_by' => $changedBy,
        ]);
    }
}
