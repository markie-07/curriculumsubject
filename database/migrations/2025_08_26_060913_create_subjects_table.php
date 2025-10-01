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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('subject_code')->unique();
            $table->string('subject_name');
            $table->string('subject_type');
            $table->integer('subject_unit');
            $table->json('lessons')->nullable();
            $table->integer('contact_hours')->nullable();
            $table->string('prerequisites')->nullable();
            $table->string('pre_requisite_to')->nullable();
            $table->text('course_description')->nullable();
            $table->json('program_mapping_grid')->nullable();
            $table->json('course_mapping_grid')->nullable();
            $table->text('pilo_outcomes')->nullable();
            $table->text('cilo_outcomes')->nullable();
            $table->text('learning_outcomes')->nullable();
            $table->text('basic_readings')->nullable();
            $table->text('extended_readings')->nullable();
            $table->text('course_assessment')->nullable();
            $table->text('committee_members')->nullable();
            $table->text('consultation_schedule')->nullable();
            $table->string('prepared_by')->nullable();
            $table->string('reviewed_by')->nullable();
            $table->string('approved_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};