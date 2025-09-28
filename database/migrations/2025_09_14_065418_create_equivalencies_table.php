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
        Schema::create('equivalencies', function (Blueprint $table) {
            $table->id();
            $table->string('source_subject_name');
            $table->unsignedBigInteger('equivalent_subject_id');
            $table->timestamps();

            // This creates a link to your subjects table and deletes the equivalency if the subject is deleted.
            $table->foreign('equivalent_subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equivalencies');
    }
};