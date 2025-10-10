<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CurriculumChangeLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, let's create the table manually if it doesn't exist
        DB::statement("
            CREATE TABLE IF NOT EXISTS curriculum_change_logs (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                curriculum_id BIGINT UNSIGNED NOT NULL,
                action_type VARCHAR(255) NOT NULL,
                subject_id BIGINT UNSIGNED NULL,
                subject_name VARCHAR(255) NULL,
                subject_code VARCHAR(255) NULL,
                old_values JSON NULL,
                new_values JSON NULL,
                change_description TEXT NOT NULL,
                changed_by BIGINT UNSIGNED NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                INDEX idx_curriculum_created (curriculum_id, created_at),
                INDEX idx_action_created (action_type, created_at),
                INDEX idx_created (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Get first curriculum and user for testing
        $curriculum = DB::table('curriculums')->first();
        $user = DB::table('users')->first();
        $subjects = DB::table('subjects')->limit(5)->get();

        if (!$curriculum || !$user || $subjects->isEmpty()) {
            $this->command->info('No curriculum, user, or subjects found. Please seed basic data first.');
            return;
        }

        $this->command->info("Creating change log entries for curriculum: {$curriculum->curriculum}");

        // Clear existing change logs for this curriculum
        DB::table('curriculum_change_logs')->where('curriculum_id', $curriculum->id)->delete();

        $changeLogEntries = [];

        // Create sample change log entries
        foreach ($subjects as $index => $subject) {
            $daysAgo = $index + 1;
            
            // Subject Added
            $changeLogEntries[] = [
                'curriculum_id' => $curriculum->id,
                'action_type' => 'subject_added',
                'subject_id' => $subject->id,
                'subject_name' => $subject->subject_name,
                'subject_code' => $subject->subject_code,
                'old_values' => null,
                'new_values' => json_encode([
                    'year' => 1,
                    'semester' => 1,
                    'subject_type' => $subject->subject_type,
                    'subject_unit' => $subject->subject_unit
                ]),
                'change_description' => "Added subject '{$subject->subject_name}' to Year 1, Semester 1",
                'changed_by' => $user->id,
                'created_at' => Carbon::now()->subDays($daysAgo)->subHours(2),
                'updated_at' => Carbon::now()->subDays($daysAgo)->subHours(2)
            ];

            // Subject Updated (if not the first subject)
            if ($index > 0) {
                $changeLogEntries[] = [
                    'curriculum_id' => $curriculum->id,
                    'action_type' => 'subject_updated',
                    'subject_id' => $subject->id,
                    'subject_name' => $subject->subject_name,
                    'subject_code' => $subject->subject_code,
                    'old_values' => json_encode([
                        'year' => 1,
                        'semester' => 1
                    ]),
                    'new_values' => json_encode([
                        'year' => 1,
                        'semester' => 2
                    ]),
                    'change_description' => "Updated subject '{$subject->subject_name}' (Semester: 1 → 2)",
                    'changed_by' => $user->id,
                    'created_at' => Carbon::now()->subDays($daysAgo)->subHours(1),
                    'updated_at' => Carbon::now()->subDays($daysAgo)->subHours(1)
                ];
            }

            // Subject Removed (for last subject)
            if ($index === $subjects->count() - 1) {
                $changeLogEntries[] = [
                    'curriculum_id' => $curriculum->id,
                    'action_type' => 'subject_removed',
                    'subject_id' => $subject->id,
                    'subject_name' => $subject->subject_name,
                    'subject_code' => $subject->subject_code,
                    'old_values' => json_encode([
                        'year' => 1,
                        'semester' => 2,
                        'subject_type' => $subject->subject_type,
                        'subject_unit' => $subject->subject_unit
                    ]),
                    'new_values' => null,
                    'change_description' => "Removed subject '{$subject->subject_name}' from Year 1, Semester 2",
                    'changed_by' => $user->id,
                    'created_at' => Carbon::now()->subDays($daysAgo)->subMinutes(30),
                    'updated_at' => Carbon::now()->subDays($daysAgo)->subMinutes(30)
                ];
            }
        }

        // Add a bulk update entry
        $changeLogEntries[] = [
            'curriculum_id' => $curriculum->id,
            'action_type' => 'bulk_update',
            'subject_id' => null,
            'subject_name' => null,
            'subject_code' => null,
            'old_values' => null,
            'new_values' => null,
            'change_description' => 'Curriculum updated: 3 subjects added, 1 subject removed',
            'changed_by' => $user->id,
            'created_at' => Carbon::now()->subHours(1),
            'updated_at' => Carbon::now()->subHours(1)
        ];

        // Insert all change log entries
        DB::table('curriculum_change_logs')->insert($changeLogEntries);

        $this->command->info('Created ' . count($changeLogEntries) . ' change log entries');

        // Create entries for a second curriculum if available
        $curriculum2 = DB::table('curriculums')->where('id', '!=', $curriculum->id)->first();
        if ($curriculum2) {
            $this->command->info("Creating change log entries for second curriculum: {$curriculum2->curriculum}");
            
            $moreEntries = [
                [
                    'curriculum_id' => $curriculum2->id,
                    'action_type' => 'subject_added',
                    'subject_id' => $subjects->first()->id,
                    'subject_name' => $subjects->first()->subject_name,
                    'subject_code' => $subjects->first()->subject_code,
                    'old_values' => null,
                    'new_values' => json_encode([
                        'year' => 2,
                        'semester' => 1,
                        'subject_type' => $subjects->first()->subject_type,
                        'subject_unit' => $subjects->first()->subject_unit
                    ]),
                    'change_description' => "Added subject '{$subjects->first()->subject_name}' to Year 2, Semester 1",
                    'changed_by' => $user->id,
                    'created_at' => Carbon::now()->subDays(2),
                    'updated_at' => Carbon::now()->subDays(2)
                ],
                [
                    'curriculum_id' => $curriculum2->id,
                    'action_type' => 'bulk_update',
                    'subject_id' => null,
                    'subject_name' => null,
                    'subject_code' => null,
                    'old_values' => null,
                    'new_values' => null,
                    'change_description' => 'Curriculum restructured: subjects reorganized across semesters',
                    'changed_by' => $user->id,
                    'created_at' => Carbon::now()->subDays(1),
                    'updated_at' => Carbon::now()->subDays(1)
                ]
            ];

            DB::table('curriculum_change_logs')->insert($moreEntries);
            $this->command->info('Created ' . count($moreEntries) . ' additional change log entries');
        }

        $this->command->info('Change log seeding completed!');
    }
}
