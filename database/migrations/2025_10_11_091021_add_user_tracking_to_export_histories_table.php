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
        if (Schema::hasTable('export_histories')) {
            Schema::table('export_histories', function (Blueprint $table) {
                if (!Schema::hasColumn('export_histories', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('curriculum_id');
                }
                if (!Schema::hasColumn('export_histories', 'exported_by_name')) {
                    $table->string('exported_by_name')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('export_histories', 'exported_by_email')) {
                    $table->string('exported_by_email')->nullable()->after('exported_by_name');
                }
                
                // Only add foreign key if user_id column exists and foreign key doesn't exist
                if (Schema::hasColumn('export_histories', 'user_id')) {
                    // Check if foreign key constraint doesn't already exist using raw SQL
                    $databaseName = config('database.connections.mysql.database');
                    $foreignKeyExists = \DB::select("
                        SELECT COUNT(*) as count 
                        FROM information_schema.KEY_COLUMN_USAGE 
                        WHERE TABLE_SCHEMA = ? 
                        AND TABLE_NAME = 'export_histories' 
                        AND COLUMN_NAME = 'user_id' 
                        AND REFERENCED_TABLE_NAME IS NOT NULL
                    ", [$databaseName]);
                    
                    if ($foreignKeyExists[0]->count == 0) {
                        $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('export_histories')) {
            Schema::table('export_histories', function (Blueprint $table) {
                if (Schema::hasColumn('export_histories', 'user_id')) {
                    // Check if foreign key exists before trying to drop it using raw SQL
                    $databaseName = config('database.connections.mysql.database');
                    $foreignKeyExists = \DB::select("
                        SELECT COUNT(*) as count 
                        FROM information_schema.KEY_COLUMN_USAGE 
                        WHERE TABLE_SCHEMA = ? 
                        AND TABLE_NAME = 'export_histories' 
                        AND COLUMN_NAME = 'user_id' 
                        AND REFERENCED_TABLE_NAME IS NOT NULL
                    ", [$databaseName]);
                    
                    if ($foreignKeyExists[0]->count > 0) {
                        $table->dropForeign(['user_id']);
                    }
                    $table->dropColumn('user_id');
                }
                if (Schema::hasColumn('export_histories', 'exported_by_name')) {
                    $table->dropColumn('exported_by_name');
                }
                if (Schema::hasColumn('export_histories', 'exported_by_email')) {
                    $table->dropColumn('exported_by_email');
                }
            });
        }
    }
};
