<?php

namespace App\Services;

use App\Models\Curriculum;
use App\Models\CurriculumHistory;
use Illuminate\Support\Facades\Auth;

class CurriculumVersionService
{
    /**
     * Create a snapshot when curriculum is updated
     */
    public static function createSnapshotOnUpdate($curriculumId, $changeDescription = null)
    {
        try {
            $curriculum = Curriculum::with('subjects')->find($curriculumId);
            
            if (!$curriculum) {
                return false;
            }

            // Prepare snapshot data
            $snapshotData = [
                'curriculum_info' => [
                    'id' => $curriculum->id,
                    'curriculum' => $curriculum->curriculum,
                    'program_code' => $curriculum->program_code,
                    'academic_year' => $curriculum->academic_year,
                    'year_level' => $curriculum->year_level,
                    'updated_at' => $curriculum->updated_at
                ],
                'subjects' => $curriculum->subjects->map(function ($subject) {
                    return [
                        'id' => $subject->id,
                        'subject_name' => $subject->subject_name,
                        'subject_code' => $subject->subject_code,
                        'subject_unit' => $subject->subject_unit,
                        'subject_type' => $subject->subject_type,
                        'year' => $subject->pivot->year,
                        'semester' => $subject->pivot->semester,
                        'mapped_at' => $subject->pivot->created_at
                    ];
                })->toArray(),
                'metadata' => [
                    'total_subjects' => $curriculum->subjects->count(),
                    'total_units' => $curriculum->subjects->sum('subject_unit'),
                    'subjects_by_type' => $curriculum->subjects->groupBy('subject_type')->map->count(),
                    'subjects_by_year' => $curriculum->subjects->groupBy('pivot.year')->map->count()
                ]
            ];

            return CurriculumHistory::createSnapshot(
                $curriculumId,
                $snapshotData,
                $changeDescription ?? 'Curriculum updated',
                Auth::id()
            );

        } catch (\Exception $e) {
            \Log::error('Failed to create curriculum snapshot: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create snapshot when subject is added to curriculum
     */
    public static function createSnapshotOnSubjectAdd($curriculumId, $subjectName)
    {
        return self::createSnapshotOnUpdate(
            $curriculumId, 
            "Subject '{$subjectName}' added to curriculum"
        );
    }

    /**
     * Create snapshot when subject is retrieved from history
     */
    public static function createSnapshotOnSubjectRetrieve($curriculumId, $subjectName)
    {
        return self::createSnapshotOnUpdate(
            $curriculumId, 
            "Subject '{$subjectName}' retrieved from history"
        );
    }

    /**
     * Create snapshot when subject is removed from curriculum
     */
    public static function createSnapshotOnSubjectRemove($curriculumId, $subjectName, $removedSubjectData = null)
    {
        try {
            $curriculum = Curriculum::with(['subjects' => function($query) {
                $query->withPivot('year', 'semester', 'created_at');
            }])->findOrFail($curriculumId);

            // Create snapshot data including removed subject info
            $snapshotData = [
                'curriculum_id' => $curriculumId,
                'curriculum_name' => $curriculum->curriculum,
                'program_code' => $curriculum->program_code,
                'academic_year' => $curriculum->academic_year,
                'year_level' => $curriculum->year_level,
                'subjects' => $curriculum->subjects->map(function ($subject) {
                    return [
                        'id' => $subject->id,
                        'subject_name' => $subject->subject_name,
                        'subject_code' => $subject->subject_code,
                        'subject_type' => $subject->subject_type,
                        'subject_unit' => $subject->subject_unit,
                        'year' => $subject->pivot->year,
                        'semester' => $subject->pivot->semester,
                        'mapped_at' => $subject->pivot->created_at
                    ];
                })->toArray(),
                'removed_subject' => $removedSubjectData ? [
                    'subject_name' => $removedSubjectData['subject_name'] ?? $subjectName,
                    'subject_code' => $removedSubjectData['subject_code'] ?? 'N/A',
                    'subject_type' => $removedSubjectData['subject_type'] ?? 'Unknown',
                    'subject_unit' => $removedSubjectData['subject_unit'] ?? 0,
                    'year' => $removedSubjectData['year'] ?? 1,
                    'semester' => $removedSubjectData['semester'] ?? 1,
                ] : null,
                'metadata' => [
                    'total_subjects' => $curriculum->subjects->count(),
                    'total_units' => $curriculum->subjects->sum('subject_unit'),
                    'subjects_by_type' => $curriculum->subjects->groupBy('subject_type')->map->count(),
                    'subjects_by_year' => $curriculum->subjects->groupBy('pivot.year')->map->count(),
                    'action' => 'remove',
                    'removed_subject_name' => $subjectName
                ]
            ];

            return CurriculumHistory::createSnapshot(
                $curriculumId,
                $snapshotData,
                "Subject '{$subjectName}' removed from curriculum",
                Auth::id()
            );

        } catch (\Exception $e) {
            \Log::error('Failed to create removal snapshot: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create snapshot when subject mapping is updated
     */
    public static function createSnapshotOnSubjectUpdate($curriculumId, $subjectName, $changes)
    {
        $changeDescription = "Subject '{$subjectName}' updated";
        if (!empty($changes)) {
            $changeList = [];
            foreach ($changes as $field => $change) {
                $changeList[] = "{$field}: {$change['old']} → {$change['new']}";
            }
            $changeDescription .= " (" . implode(', ', $changeList) . ")";
        }

        return self::createSnapshotOnUpdate($curriculumId, $changeDescription);
    }

    /**
     * Get version comparison data
     */
    public static function getVersionComparison($curriculumId, $version1Id, $version2Id)
    {
        $version1 = CurriculumHistory::where('curriculum_id', $curriculumId)
            ->where('id', $version1Id)
            ->first();
            
        $version2 = CurriculumHistory::where('curriculum_id', $curriculumId)
            ->where('id', $version2Id)
            ->first();

        if (!$version1 || !$version2) {
            return null;
        }

        $subjects1 = collect($version1->snapshot_data['subjects'] ?? []);
        $subjects2 = collect($version2->snapshot_data['subjects'] ?? []);

        // Find differences
        $added = $subjects2->whereNotIn('id', $subjects1->pluck('id'));
        $removed = $subjects1->whereNotIn('id', $subjects2->pluck('id'));
        $modified = collect();

        // Check for modifications
        foreach ($subjects1 as $subject1) {
            $subject2 = $subjects2->firstWhere('id', $subject1['id']);
            if ($subject2) {
                $changes = [];
                foreach (['subject_name', 'subject_code', 'subject_unit', 'year', 'semester', 'subject_type'] as $field) {
                    if (($subject1[$field] ?? null) !== ($subject2[$field] ?? null)) {
                        $changes[$field] = [
                            'old' => $subject1[$field] ?? null,
                            'new' => $subject2[$field] ?? null
                        ];
                    }
                }
                if (!empty($changes)) {
                    $modified->push([
                        'subject' => $subject2,
                        'changes' => $changes
                    ]);
                }
            }
        }

        return [
            'version1' => [
                'id' => $version1->id,
                'version_number' => $version1->version_number,
                'created_at' => $version1->created_at->format('M d, Y \a\t g:i A'),
                'change_description' => $version1->change_description
            ],
            'version2' => [
                'id' => $version2->id,
                'version_number' => $version2->version_number,
                'created_at' => $version2->created_at->format('M d, Y \a\t g:i A'),
                'change_description' => $version2->change_description
            ],
            'changes' => [
                'added' => $added->values(),
                'removed' => $removed->values(),
                'modified' => $modified->values()
            ],
            'summary' => [
                'total_changes' => $added->count() + $removed->count() + $modified->count(),
                'subjects_added' => $added->count(),
                'subjects_removed' => $removed->count(),
                'subjects_modified' => $modified->count()
            ]
        ];
    }

    /**
     * Clean up old versions (keep only last N versions)
     */
    public static function cleanupOldVersions($curriculumId, $keepVersions = 10)
    {
        $versions = CurriculumHistory::where('curriculum_id', $curriculumId)
            ->orderBy('version_number', 'desc')
            ->skip($keepVersions)
            ->pluck('id');

        if ($versions->isNotEmpty()) {
            CurriculumHistory::whereIn('id', $versions)->delete();
            return $versions->count();
        }

        return 0;
    }
}
