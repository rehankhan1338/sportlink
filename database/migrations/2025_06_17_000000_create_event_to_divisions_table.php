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
        Schema::create('event_to_divisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('name');
            $table->enum('gender', ['male', 'female', 'any']);
            $table->integer('min_age');
            $table->integer('max_age');
            $table->decimal('min_weight', 5, 2);
            $table->decimal('max_weight', 5, 2);
            $table->string('belt_level')->nullable();
            $table->string('bracket_type');
            $table->integer('match_duration_min')->nullable();
            $table->time('start_time')->nullable();
            $table->integer('mat_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_to_divisions');
    }
}; 