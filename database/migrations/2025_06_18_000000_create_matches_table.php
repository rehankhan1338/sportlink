<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop the existing table if it exists
        Schema::dropIfExists('matches');

        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('division_id')->constrained('event_to_divisions')->onDelete('cascade');
            $table->string('division_type'); // single_elimination, double_elimination, round_robin
            $table->string('bracket_type')->nullable(); // upper, lower, bronze - for elimination brackets
            $table->integer('round_number');
            $table->string('mat_name'); // e.g., Mat 2-1
            $table->foreignId('player1_id')->nullable()->constrained('profiles')->onDelete('set null');
            $table->foreignId('player2_id')->nullable()->constrained('profiles')->onDelete('set null');
            $table->foreignId('winner_id')->nullable()->constrained('profiles')->onDelete('set null');
            $table->foreignId('loser_id')->nullable()->constrained('profiles')->onDelete('set null');
            $table->foreignId('parent_match1_id')->nullable()->references('id')->on('matches')->onDelete('set null');
            $table->foreignId('parent_match2_id')->nullable()->references('id')->on('matches')->onDelete('set null');
            $table->string('status')->default('pending'); // pending, in_progress, completed
            $table->json('score_details')->nullable();
            $table->dateTime('scheduled_time');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('matches');
    }
}; 