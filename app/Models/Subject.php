<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_code',
        'subject_name',
        'subject_type',
        'subject_unit',
        'lessons',
        'contact_hours',
        'prerequisites',
        'pre_requisite_to',
        'course_description',
        'program_mapping_grid',
        'course_mapping_grid',
        'pilo_outcomes',
        'cilo_outcomes',
        'learning_outcomes',
        'basic_readings',
        'extended_readings',
        'course_assessment',
        'course_policies',
        'committee_members',
        'consultation_schedule',
        'prepared_by',
        'reviewed_by',
        'approved_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'lessons' => 'array',
        'program_mapping_grid' => 'array',
        'course_mapping_grid' => 'array',
    ];

    /**
     * The curriculums that belong to the subject.
     */
    public function curriculums(): BelongsToMany
    {
        return $this->belongsToMany(Curriculum::class, 'curriculum_subject')
            ->withPivot('year', 'semester')
            ->withTimestamps();
    }

    /**
     * Get the prerequisites for the subject.
     */
    public function prerequisites(): HasMany
    {
        return $this->hasMany(Prerequisite::class, 'subject_code', 'subject_code');
    }

    /**
     * Get the grade for the subject.
     */
    public function grade(): HasOne
    {
        return $this->hasOne(Grade::class, 'subject_id', 'id');
    }

    /**
     * Get the grades for the subject.
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get the version history for the subject.
     */
    public function versions(): HasMany
    {
        return $this->hasMany(SubjectVersion::class);
    }
}
