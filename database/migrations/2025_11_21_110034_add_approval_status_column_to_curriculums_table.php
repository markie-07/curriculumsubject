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
        Schema::table('curriculums', function (Blueprint $table) {
            // if (!Schema::hasColumn('curriculums', 'approval_status')) {
            //     $table->string('approval_status')->default('processing')->after('version_status');
            // }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('curriculums', function (Blueprint $table) {
            if (Schema::hasColumn('curriculums', 'approval_status')) {
                $table->dropColumn('approval_status');
            }
        });
    }
};
