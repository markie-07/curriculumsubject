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
        Schema::table('subjects', function (Blueprint $table) {
            // Add the new columns after 'course_description'
            $table->json('program_mapping_grid')->nullable()->after('course_description');
            $table->json('course_mapping_grid')->nullable()->after('program_mapping_grid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Define how to reverse the changes if needed
            $table->dropColumn('program_mapping_grid');
            $table->dropColumn('course_mapping_grid');
        });
    }
};