<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::create('organizations', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('company')->nullable();
    $table->string('first_name');
    $table->string('last_name');
    $table->string('address')->nullable();
    $table->string('zip')->nullable();
    $table->string('city')->nullable();
    $table->string('country')->nullable();
    $table->string('email')->nullable();
    $table->string('phone')->nullable();
    $table->string('card_number')->nullable(); // Do not store real card numbers in production
    $table->timestamps();
    });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
