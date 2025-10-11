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
        Schema::table('export_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('curriculum_id');
            $table->string('exported_by_name')->nullable()->after('user_id');
            $table->string('exported_by_email')->nullable()->after('exported_by_name');
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('export_histories', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'exported_by_name', 'exported_by_email']);
        });
    }
};
