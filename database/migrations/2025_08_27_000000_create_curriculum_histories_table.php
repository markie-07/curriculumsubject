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
        if (!Schema::hasTable('curriculum_histories')) {
            Schema::create('curriculum_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('curriculum_id')->constrained('curriculums')->onDelete('cascade');
                $table->integer('version_number');
                $table->json('snapshot_data'); // Store the complete curriculum state
                $table->string('change_description')->nullable();
                $table->foreignId('changed_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
                
                $table->index(['curriculum_id', 'version_number']);
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculum_histories');
    }
};
