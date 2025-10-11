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
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'status')) {
                    $table->enum('status', ['active', 'inactive'])->default('active')->after('role');
                }
                if (!Schema::hasColumn('users', 'last_activity')) {
                    $table->timestamp('last_activity')->nullable()->after('status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'status')) {
                    $table->dropColumn('status');
                }
                if (Schema::hasColumn('users', 'last_activity')) {
                    $table->dropColumn('last_activity');
                }
            });
        }
    }
};
