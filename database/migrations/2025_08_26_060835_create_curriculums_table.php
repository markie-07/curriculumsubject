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
        if (!Schema::hasTable('curriculums')) {
            Schema::create('curriculums', function (Blueprint $table) {
                $table->id();
                $table->string('curriculum');
                $table->string('program_code')->unique();
                $table->string('academic_year');
                $table->string('year_level'); // Can be 'Senior High' or 'College'
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculums');
    }
};

