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
        if (!Schema::hasTable('grade_versions')) {
            Schema::create('grade_versions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('grade_id')->constrained('grades')->onDelete('cascade');
                $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
                $table->unsignedBigInteger('version_number');
                $table->json('components'); // Store the complete grade components as JSON
                $table->foreignId('curriculum_id')->nullable()->constrained('curriculums')->onDelete('cascade');
                $table->string('course_type')->nullable(); // 'minor' or 'major'
                $table->text('change_reason')->nullable();
                $table->string('changed_by')->nullable();
                $table->timestamps();
                
                // Index for faster lookups
                $table->index(['grade_id', 'version_number']);
                $table->index('subject_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_versions');
    }
};
