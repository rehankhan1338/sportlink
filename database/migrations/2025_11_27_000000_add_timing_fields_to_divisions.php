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
            if (!Schema::hasColumn('event_to_divisions', 'start_time')) {
                $table->time('start_time')->nullable()->after('match_duration_min');
            }
            if (!Schema::hasColumn('event_to_divisions', 'mat_number')) {
                $table->integer('mat_number')->nullable()->after('start_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_to_divisions', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'mat_number']);
        });
    }
}; 