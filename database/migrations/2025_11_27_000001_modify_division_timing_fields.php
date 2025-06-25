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
        Schema::table('event_to_divisions', function (Blueprint $table) {
            // First drop the existing columns
            $table->dropColumn(['start_time', 'mat_number']);
            
            // Then add them back with the correct types
            $table->dateTime('start_time')->nullable()->after('match_duration_min');
            $table->string('mat_number', 50)->nullable()->after('start_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_to_divisions', function (Blueprint $table) {
            // First drop the new columns
            $table->dropColumn(['start_time', 'mat_number']);
            
            // Then add them back with the original types
            $table->time('start_time')->nullable()->after('match_duration_min');
            $table->integer('mat_number')->nullable()->after('start_time');
        });
    }
}; 