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
        Schema::create('visitor_sessions', function (Blueprint $table) {
            $table->id();
            // $table->string('session_id')->unique(); // Replaced by session_token in the seeder
            $table->string('session_token')->unique(); // Token for visitor identification
            $table->string('ip_address', 45);
            $table->text('user_agent');
            $table->string('device_type')->nullable(); // ADDED: To track if mobile, desktop, or tablet
            $table->timestamp('session_start');
            $table->timestamp('session_end')->nullable(); // Session end time
            $table->timestamps();

            $table->index('session_token');
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_sessions');
    }
};
