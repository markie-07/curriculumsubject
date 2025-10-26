<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Curriculum extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'curriculums';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'curriculum',
        'program_code',
        'academic_year',
        'year_level',
        'compliance',
        'memorandum_year',
        'memorandum_category',
        'memorandum',
        'semester_units',
        'total_units',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'semester_units' => 'array',
        'total_units' => 'decimal:2',
    ];

    /**
     * The subjects that belong to the curriculum.
     */
    public function subjects(): BelongsToMany
    {
        // FIX: Explicitly define the pivot table name AND the foreign keys.
        // This is the final step to ensure it matches your database exactly.
        return $this->belongsToMany(Subject::class, 'curriculum_subject', 'curriculum_id', 'subject_id')
            ->withPivot('year', 'semester')
            ->withTimestamps();
    }
}

