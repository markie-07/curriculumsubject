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
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('ip_address')->index();
            $table->integer('attempts')->default(1);
            $table->integer('lockout_count')->default(0); // Track number of lockouts
            $table->timestamp('last_attempt_at');
            $table->timestamp('locked_until')->nullable();
            $table->timestamp('first_lockout_at')->nullable(); // Track when first lockout occurred
            $table->timestamps();
            
            // Composite index for efficient lookups
            $table->index(['email', 'ip_address']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_attempts');
    }
};
