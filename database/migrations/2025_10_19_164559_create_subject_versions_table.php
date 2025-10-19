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
        Schema::create('subject_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id');
            $table->integer('version_number');
            $table->string('subject_name');
            $table->string('subject_code');
            $table->string('subject_type');
            $table->integer('subject_unit');
            $table->integer('contact_hours')->nullable();
            $table->text('prerequisites')->nullable();
            $table->text('pre_requisite_to')->nullable();
            $table->text('course_description')->nullable();
            $table->json('program_mapping_grid')->nullable();
            $table->json('course_mapping_grid')->nullable();
            $table->text('pilo_outcomes')->nullable();
            $table->text('cilo_outcomes')->nullable();
            $table->text('learning_outcomes')->nullable();
            $table->json('lessons')->nullable();
            $table->text('basic_readings')->nullable();
            $table->text('extended_readings')->nullable();
            $table->text('course_assessment')->nullable();
            $table->string('committee_members')->nullable();
            $table->text('consultation_schedule')->nullable();
            $table->string('prepared_by')->nullable();
            $table->string('reviewed_by')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('change_reason')->nullable();
            $table->string('changed_by')->nullable();
            $table->timestamps();

            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->index(['subject_id', 'version_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_versions');
    }
};
