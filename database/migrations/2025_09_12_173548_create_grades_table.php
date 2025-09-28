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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id')->unique();

            // This single JSON column will store the entire nested grade structure
            // (Prelim, Midterm, Finals, and their sub-components).
            $table->json('components')->nullable();

            $table->timestamps();

            // Defines the relationship between the 'grades' and 'subjects' tables
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};