<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curriculum;
use App\Models\CurriculumHistory;
use App\Models\User;
use Carbon\Carbon;

class CurriculumHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first curriculum and user for testing
        $curriculum = Curriculum::with('subjects')->first();
        $user = User::first();

        if (!$curriculum || !$user) {
            $this->command->info('No curriculum or user found. Please seed curriculums and users first.');
            return;
        }

        $this->command->info("Creating version history for curriculum: {$curriculum->curriculum}");

        // Create version 1 - Initial version
        $version1Data = [
            'curriculum_info' => [
                'id' => $curriculum->id,
                'curriculum' => $curriculum->curriculum,
                'program_code' => $curriculum->program_code,
                'academic_year' => $curriculum->academic_year,
                'year_level' => $curriculum->year_level
            ],
            'subjects' => $curriculum->subjects->take(5)->map(function ($subject) {
                return [
                    'id' => $subject->id,
                    'subject_name' => $subject->subject_name,
                    'subject_code' => $subject->subject_code,
                    'subject_unit' => $subject->subject_unit,
                    'subject_type' => $subject->subject_type,
                    'year' => $subject->pivot->year ?? 1,
                    'semester' => $subject->pivot->semester ?? 1
                ];
            })->toArray()
        ];

        CurriculumHistory::create([
            'curriculum_id' => $curriculum->id,
            'version_number' => 1,
            'snapshot_data' => $version1Data,
            'change_description' => 'Initial curriculum setup',
            'changed_by' => $user->id,
            'created_at' => Carbon::now()->subDays(30),
            'updated_at' => Carbon::now()->subDays(30)
        ]);

        // Create version 2 - Added more subjects
        $version2Data = [
            'curriculum_info' => [
                'id' => $curriculum->id,
                'curriculum' => $curriculum->curriculum,
                'program_code' => $curriculum->program_code,
                'academic_year' => $curriculum->academic_year,
                'year_level' => $curriculum->year_level
            ],
            'subjects' => $curriculum->subjects->take(8)->map(function ($subject) {
                return [
                    'id' => $subject->id,
                    'subject_name' => $subject->subject_name,
                    'subject_code' => $subject->subject_code,
                    'subject_unit' => $subject->subject_unit,
                    'subject_type' => $subject->subject_type,
                    'year' => $subject->pivot->year ?? 1,
                    'semester' => $subject->pivot->semester ?? 1
                ];
            })->toArray()
        ];

        CurriculumHistory::create([
            'curriculum_id' => $curriculum->id,
            'version_number' => 2,
            'snapshot_data' => $version2Data,
            'change_description' => 'Added 3 new subjects to first year curriculum',
            'changed_by' => $user->id,
            'created_at' => Carbon::now()->subDays(20),
            'updated_at' => Carbon::now()->subDays(20)
        ]);

        // Create version 3 - Updated subject mappings
        $version3Data = [
            'curriculum_info' => [
                'id' => $curriculum->id,
                'curriculum' => $curriculum->curriculum,
                'program_code' => $curriculum->program_code,
                'academic_year' => $curriculum->academic_year,
                'year_level' => $curriculum->year_level
            ],
            'subjects' => $curriculum->subjects->take(10)->map(function ($subject, $index) {
                return [
                    'id' => $subject->id,
                    'subject_name' => $subject->subject_name,
                    'subject_code' => $subject->subject_code,
                    'subject_unit' => $subject->subject_unit,
                    'subject_type' => $subject->subject_type,
                    'year' => $index < 5 ? 1 : 2, // Mix of year 1 and 2
                    'semester' => ($index % 2) + 1 // Alternate semesters
                ];
            })->toArray()
        ];

        CurriculumHistory::create([
            'curriculum_id' => $curriculum->id,
            'version_number' => 3,
            'snapshot_data' => $version3Data,
            'change_description' => 'Reorganized subjects across years and semesters',
            'changed_by' => $user->id,
            'created_at' => Carbon::now()->subDays(10),
            'updated_at' => Carbon::now()->subDays(10)
        ]);

        // Create version 4 - Current version
        $version4Data = [
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
                    'year' => $subject->pivot->year ?? 1,
                    'semester' => $subject->pivot->semester ?? 1
                ];
            })->toArray()
        ];

        CurriculumHistory::create([
            'curriculum_id' => $curriculum->id,
            'version_number' => 4,
            'snapshot_data' => $version4Data,
            'change_description' => 'Added remaining subjects and finalized curriculum structure',
            'changed_by' => $user->id,
            'created_at' => Carbon::now()->subDays(2),
            'updated_at' => Carbon::now()->subDays(2)
        ]);

        $this->command->info('Created 4 version history records for curriculum: ' . $curriculum->curriculum);

        // Create version history for a second curriculum if available
        $curriculum2 = Curriculum::with('subjects')->where('id', '!=', $curriculum->id)->first();
        
        if ($curriculum2) {
            $this->command->info("Creating version history for second curriculum: {$curriculum2->curriculum}");
            
            // Create 2 versions for second curriculum
            for ($i = 1; $i <= 2; $i++) {
                $versionData = [
                    'curriculum_info' => [
                        'id' => $curriculum2->id,
                        'curriculum' => $curriculum2->curriculum,
                        'program_code' => $curriculum2->program_code,
                        'academic_year' => $curriculum2->academic_year,
                        'year_level' => $curriculum2->year_level
                    ],
                    'subjects' => $curriculum2->subjects->take($i * 3)->map(function ($subject) {
                        return [
                            'id' => $subject->id,
                            'subject_name' => $subject->subject_name,
                            'subject_code' => $subject->subject_code,
                            'subject_unit' => $subject->subject_unit,
                            'subject_type' => $subject->subject_type,
                            'year' => $subject->pivot->year ?? 1,
                            'semester' => $subject->pivot->semester ?? 1
                        ];
                    })->toArray()
                ];

                CurriculumHistory::create([
                    'curriculum_id' => $curriculum2->id,
                    'version_number' => $i,
                    'snapshot_data' => $versionData,
                    'change_description' => $i == 1 ? 'Initial curriculum setup' : 'Added more subjects',
                    'changed_by' => $user->id,
                    'created_at' => Carbon::now()->subDays(15 - ($i * 5)),
                    'updated_at' => Carbon::now()->subDays(15 - ($i * 5))
                ]);
            }
            
            $this->command->info('Created 2 version history records for curriculum: ' . $curriculum2->curriculum);
        }

        $this->command->info('Curriculum history seeding completed!');
    }
}
