<?php

namespace App\Http\Controllers;

use App\Models\Curriculum;
use App\Models\CurriculumHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurriculumHistoryController extends Controller
{
    /**
     * Get curriculum history versions
     */
    public function getVersions($curriculumId)
    {
        try {
            $curriculum = Curriculum::findOrFail($curriculumId);
            
            $versions = CurriculumHistory::where('curriculum_id', $curriculumId)
                ->with('user:id,name')
                ->orderBy('version_number', 'desc')
                ->get()
                ->map(function ($version) use ($curriculumId) {
                    return [
                        'id' => $version->id,
                        'version_number' => $version->version_number,
                        'change_description' => $version->change_description,
                        'changed_by' => $version->user ? $version->user->name : 'Unknown',
                        'created_at' => $version->created_at->timezone('Asia/Manila')->format('M d, Y \a\t g:i A'),
                        'created_at_raw' => $version->created_at->toISOString(),
                        'snapshot_data' => $version->snapshot_data,
                        'is_current' => $version->version_number === CurriculumHistory::getLatestVersionNumber($curriculumId)
                    ];
                });

            return response()->json([
                'success' => true,
                'curriculum' => [
                    'id' => $curriculum->id,
                    'curriculum_name' => $curriculum->curriculum,
                    'program_code' => $curriculum->program_code
                ],
                'versions' => $versions
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load curriculum versions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific version details
     */
    public function getVersionDetails($curriculumId, $versionId)
    {
        try {
            $version = CurriculumHistory::where('curriculum_id', $curriculumId)
                ->where('id', $versionId)
                ->with('user:id,name')
                ->firstOrFail();

            $snapshotData = $version->snapshot_data;
            
            // Organize subjects by year and semester
            $organizedSubjects = [];
            if (isset($snapshotData['subjects'])) {
                foreach ($snapshotData['subjects'] as $subject) {
                    $year = $subject['year'] ?? 1;
                    $semester = $subject['semester'] ?? 1;
                    
                    if (!isset($organizedSubjects[$year])) {
                        $organizedSubjects[$year] = [];
                    }
                    if (!isset($organizedSubjects[$year][$semester])) {
                        $organizedSubjects[$year][$semester] = [];
                    }
                    
                    $organizedSubjects[$year][$semester][] = $subject;
                }
            }

            return response()->json([
                'success' => true,
                'version' => [
                    'id' => $version->id,
                    'version_number' => $version->version_number,
                    'change_description' => $version->change_description,
                    'changed_by' => $version->user ? $version->user->name : 'Unknown',
                    'created_at' => $version->created_at->timezone('Asia/Manila')->format('M d, Y \a\t g:i A'),
                    'subjects' => $organizedSubjects,
                    'total_subjects' => count($snapshotData['subjects'] ?? [])
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load version details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new version snapshot
     */
    public function createSnapshot(Request $request, $curriculumId)
    {
        try {
            $curriculum = Curriculum::with('subjects')->findOrFail($curriculumId);
            
            // Prepare snapshot data
            $snapshotData = [
                'curriculum_info' => [
                    'id' => $curriculum->id,
                    'curriculum' => $curriculum->curriculum,
                    'program_code' => $curriculum->program_code,
                    'academic_year' => $curriculum->academic_year,
                    'year_level' => $curriculum->year_level
                ],
                'subjects' => $curriculum->subjects->map(function ($subject) {
                    return [
                        'id' => $subject->id,
                        'subject_name' => $subject->subject_name,
                        'subject_code' => $subject->subject_code,
                        'subject_unit' => $subject->subject_unit,
                        'subject_type' => $subject->subject_type,
                        'year' => $subject->pivot->year,
                        'semester' => $subject->pivot->semester
                    ];
                })->toArray()
            ];

            $version = CurriculumHistory::createSnapshot(
                $curriculumId,
                $snapshotData,
                $request->input('change_description', 'Manual snapshot created'),
                Auth::id()
            );

            return response()->json([
                'success' => true,
                'message' => 'Snapshot created successfully',
                'version' => [
                    'id' => $version->id,
                    'version_number' => $version->version_number,
                    'created_at' => $version->created_at->timezone('Asia/Manila')->format('M d, Y \a\t g:i A')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create snapshot: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Compare two versions
     */
    public function compareVersions($curriculumId, $version1Id, $version2Id)
    {
        try {
            $version1 = CurriculumHistory::where('curriculum_id', $curriculumId)
                ->where('id', $version1Id)
                ->firstOrFail();
                
            $version2 = CurriculumHistory::where('curriculum_id', $curriculumId)
                ->where('id', $version2Id)
                ->firstOrFail();

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
                    foreach (['subject_name', 'subject_code', 'subject_unit', 'year', 'semester'] as $field) {
                        if ($subject1[$field] !== $subject2[$field]) {
                            $changes[$field] = [
                                'old' => $subject1[$field],
                                'new' => $subject2[$field]
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

            return response()->json([
                'success' => true,
                'comparison' => [
                    'version1' => [
                        'id' => $version1->id,
                        'version_number' => $version1->version_number,
                        'created_at' => $version1->created_at->timezone('Asia/Manila')->format('M d, Y \a\t g:i A')
                    ],
                    'version2' => [
                        'id' => $version2->id,
                        'version_number' => $version2->version_number,
                        'created_at' => $version2->created_at->timezone('Asia/Manila')->format('M d, Y \a\t g:i A')
                    ],
                    'changes' => [
                        'added' => $added->values(),
                        'removed' => $removed->values(),
                        'modified' => $modified->values()
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to compare versions: ' . $e->getMessage()
            ], 500);
        }
    }
}
