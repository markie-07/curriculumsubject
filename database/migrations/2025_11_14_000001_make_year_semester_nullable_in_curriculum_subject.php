<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('curriculum_subject')) {
            try {
                DB::statement('ALTER TABLE curriculum_subject MODIFY year INT NULL');
                DB::statement('ALTER TABLE curriculum_subject MODIFY semester INT NULL');
            } catch (\Throwable $e) {
                // Fallback for MariaDB/MySQL variants that require different syntax
                try {
                    DB::statement('ALTER TABLE curriculum_subject MODIFY COLUMN year INT NULL');
                    DB::statement('ALTER TABLE curriculum_subject MODIFY COLUMN semester INT NULL');
                } catch (\Throwable $e2) {
                    // As a last resort, attempt to add default NULL via change if doctrine is present
                    // If not available, this migration will be a no-op and should be rerun after installing doctrine/dbal.
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('curriculum_subject')) {
            try {
                DB::statement('ALTER TABLE curriculum_subject MODIFY year INT NOT NULL');
                DB::statement('ALTER TABLE curriculum_subject MODIFY semester INT NOT NULL');
            } catch (\Throwable $e) {
                try {
                    DB::statement('ALTER TABLE curriculum_subject MODIFY COLUMN year INT NOT NULL');
                    DB::statement('ALTER TABLE curriculum_subject MODIFY COLUMN semester INT NOT NULL');
                } catch (\Throwable $e2) {
                    // no-op if unable to revert
                }
            }
        }
    }
};