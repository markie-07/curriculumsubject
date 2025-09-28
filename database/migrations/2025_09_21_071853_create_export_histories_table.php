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
    Schema::create('export_histories', function (Blueprint $table) {
        $table->id();
        // We are making the foreign key definition more explicit here
        $table->unsignedBigInteger('curriculum_id');
        $table->string('file_name');
        $table->string('format');
        $table->timestamps();

        // Explicitly define the foreign key relationship
        $table->foreign('curriculum_id')
              ->references('id')
              ->on('curriculums')
              ->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('export_histories');
    }
};