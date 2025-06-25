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
        Schema::create('team_participants', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID (Primary Key)
            $table->unsignedBigInteger('team_id'); // Foreign key for teams
            $table->unsignedBigInteger('participant_id'); // Foreign key for participants
            $table->timestamps(); // Created_at and Updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_participants');
    }
};
