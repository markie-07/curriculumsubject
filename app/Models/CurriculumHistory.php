<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'curriculum_id',
        'version_number',
        'snapshot_data',
        'change_description',
        'changed_by',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'snapshot_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * Get the latest version number for a curriculum
     */
    public static function getLatestVersionNumber($curriculumId)
    {
        return self::where('curriculum_id', $curriculumId)
                   ->max('version_number') ?? 0;
    }

    /**
     * Create a new version snapshot
     */
    public static function createSnapshot($curriculumId, $snapshotData, $changeDescription = null, $changedBy = null)
    {
        $latestVersion = self::getLatestVersionNumber($curriculumId);
        
        return self::create([
            'curriculum_id' => $curriculumId,
            'version_number' => $latestVersion + 1,
            'snapshot_data' => $snapshotData,
            'change_description' => $changeDescription ?? 'Curriculum updated',
            'changed_by' => $changedBy ?? auth()->id()
        ]);
    }
}
