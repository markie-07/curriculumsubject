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
        Schema::table('grades', function (Blueprint $table) {
            $table->unsignedBigInteger('curriculum_id')->nullable()->after('subject_id');
            $table->enum('course_type', ['minor', 'major'])->nullable()->after('curriculum_id');
            
            // Add foreign key constraint
            $table->foreign('curriculum_id')->references('id')->on('curriculums')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign(['curriculum_id']);
            $table->dropColumn(['curriculum_id', 'course_type']);
        });
    }
};
