<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            // Add new fields
            $table->string('round_type')->after('round_number'); // semifinal, winners_round1, final, etc.
            $table->foreignId('created_by')->nullable()->after('scheduled_time')
                ->constrained('admins')->onDelete('set null');
            $table->integer('match_number')->after('id'); // For display/reference
            
            // Modify existing fields
            $table->string('status')->default('pending')
                ->comment('pending, in_progress, completed, walkover')
                ->change();
            
            // Add audit fields
            $table->json('audit_log')->nullable()->after('created_by')
                ->comment('Track changes: who, what, when');
        });
    }

    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn([
                'round_type',
                'created_by',
                'match_number',
                'audit_log'
            ]);
            
            $table->string('status')->default('pending')->change();
        });
    }
}; 