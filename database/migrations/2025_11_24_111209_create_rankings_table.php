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
        Schema::create('rankings', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('event_id'); // Foreign key to events table
            $table->unsignedBigInteger('category_id'); // Foreign key to categories table
            $table->unsignedBigInteger('team_id');
            $table->integer('rank'); // Rank of the participant
            $table->integer('points')->default(0); // Points scored by the participant
            $table->text('details')->nullable(); // Additional details about the ranking
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rankings');
    }
};
