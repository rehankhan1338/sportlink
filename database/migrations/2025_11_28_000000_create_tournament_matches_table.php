<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tournament_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('division_id')->constrained('tournament_divisions')->onDelete('cascade');
            $table->string('round_type'); // semifinal, quarterfinal, final, winners_round1, etc.
            $table->integer('round_number');
            $table->integer('match_number');
            $table->string('mat_number');
            $table->foreignId('competitor_1_id')->nullable()->constrained('profiles')->onDelete('set null');
            $table->foreignId('competitor_2_id')->nullable()->constrained('profiles')->onDelete('set null');
            $table->foreignId('winner_id')->nullable()->constrained('profiles')->onDelete('set null');
            $table->foreignId('loser_id')->nullable()->constrained('profiles')->onDelete('set null');
            $table->foreignId('parent_match1_id')->nullable()->references('id')->on('tournament_matches')->onDelete('set null');
            $table->foreignId('parent_match2_id')->nullable()->references('id')->on('tournament_matches')->onDelete('set null');
            $table->string('status')->default('pending'); // pending, in_progress, completed, walkover
            $table->json('score_details')->nullable();
            $table->dateTime('start_time');
            $table->foreignId('created_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->json('audit_log')->nullable();
            $table->timestamps();

            // Add indexes for common queries
            $table->index(['event_id', 'division_id', 'round_type']);
            $table->index(['status', 'start_time']);
            $table->index(['competitor_1_id', 'competitor_2_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tournament_matches');
    }
}; 