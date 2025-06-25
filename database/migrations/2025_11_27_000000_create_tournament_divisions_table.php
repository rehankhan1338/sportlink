<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tournament_divisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('name');
            $table->string('bracket_type'); // single_elimination, double_elimination, round_robin
            $table->float('min_weight')->nullable();
            $table->float('max_weight')->nullable();
            $table->integer('min_age')->nullable();
            $table->integer('max_age')->nullable();
            $table->string('gender')->nullable(); // male, female
            $table->string('belt_level')->nullable(); // white, blue, purple, brown, black
            $table->string('status')->default('draft'); // draft, published, in_progress, completed
            $table->foreignId('created_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamps();

            // Add indexes for common queries
            $table->index(['event_id', 'status'], 'td_event_status_idx');
            $table->index(['min_weight', 'max_weight'], 'td_weight_idx');
            $table->index(['min_age', 'max_age'], 'td_age_idx');
        });

        // Create pivot table for athletes in divisions
        Schema::create('tournament_division_athletes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_division_id')->constrained()->onDelete('cascade');
            $table->foreignId('profile_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('registered'); // registered, checked_in, no_show
            $table->timestamp('registration_date');
            $table->timestamps();

            // Add unique constraint with shorter name
            $table->unique(['tournament_division_id', 'profile_id'], 'tda_division_profile_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tournament_division_athletes');
        Schema::dropIfExists('tournament_divisions');
    }
}; 