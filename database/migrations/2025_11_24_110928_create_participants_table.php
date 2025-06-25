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
        Schema::create('participants', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('user_id'); // Foreign key to users table
            $table->unsignedBigInteger('event_id'); // Foreign key to events table
            $table->unsignedBigInteger('category_id'); // Foreign key to categories table
            $table->string('role')->default('competitor'); // Role of the participant (e.g., competitor, coach)
            $table->string('status')->default('registered'); // Status of the participant (e.g., registered, active, disqualified)
            $table->decimal('fee_paid', 10, 2)->default(0.00); // Amount paid for registration
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
