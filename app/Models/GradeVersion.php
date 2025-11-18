<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeVersion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'grade_id',
        'subject_id',
        'version_number',
        'components',
        'curriculum_id',
        'course_type',
        'change_reason',
        'changed_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'components' => 'array',
    ];

    /**
     * Get the grade that this version belongs to.
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * Get the subject that this version belongs to.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the curriculum that this version belongs to.
     */
    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }

    /**
     * Create a version from an existing grade.
     */
    public static function createFromGrade(Grade $grade, ?string $changeReason = null, ?string $changedBy = null): self
    {
        $latestVersion = self::where('grade_id', $grade->id)
            ->orderBy('version_number', 'desc')
            ->first();

        $versionNumber = $latestVersion ? $latestVersion->version_number + 1 : 1;

        return self::create([
            'grade_id' => $grade->id,
            'subject_id' => $grade->subject_id,
            'version_number' => $versionNumber,
            'components' => $grade->components,
            'curriculum_id' => $grade->curriculum_id,
            'course_type' => $grade->course_type,
            'change_reason' => $changeReason,
            'changed_by' => $changedBy,
        ]);
    }
}
