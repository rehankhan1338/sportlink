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
        Schema::create('brackets', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('event_id'); // Foreign key to events table
            $table->unsignedBigInteger('category_id'); // Foreign key to categories table
            $table->string('name'); // Name of the bracket
            $table->integer('round')->default(1); // Current round of the bracket
            $table->text('structure')->nullable(); // JSON structure for participants and matchups
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brackets');
    }
};
