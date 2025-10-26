<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('curriculums', function (Blueprint $table) {
            if (!Schema::hasColumn('curriculums', 'compliance')) {
                $table->string('compliance')->nullable()->after('year_level'); // CHED or DepEd
            }
            if (!Schema::hasColumn('curriculums', 'memorandum_year')) {
                $table->string('memorandum_year')->nullable()->after('compliance'); // For CHED memorandums
            }
            if (!Schema::hasColumn('curriculums', 'memorandum_category')) {
                $table->string('memorandum_category')->nullable()->after('memorandum_year'); // For DepEd memorandums
            }
            if (!Schema::hasColumn('curriculums', 'memorandum')) {
                $table->text('memorandum')->nullable()->after('memorandum_category'); // Selected memorandum text
            }
            if (!Schema::hasColumn('curriculums', 'semester_units')) {
                $table->json('semester_units')->nullable()->after('memorandum'); // Array of semester unit values
            }
            if (!Schema::hasColumn('curriculums', 'total_units')) {
                $table->decimal('total_units', 8, 2)->nullable()->after('semester_units'); // Total calculated units
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('curriculums', function (Blueprint $table) {
            $table->dropColumn([
                'compliance',
                'memorandum_year',
                'memorandum_category',
                'memorandum',
                'semester_units',
                'total_units'
            ]);
        });
    }
};
