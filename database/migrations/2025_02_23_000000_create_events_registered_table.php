<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events_registered', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('email');
            $table->decimal('weight', 5, 2);
            $table->decimal('height', 5, 2);
            $table->date('date_of_birth');
            $table->integer('age');
            $table->string('gender');
            $table->string('nationality');
            $table->string('country_of_residence');
            $table->string('phone');
            $table->text('address');
            $table->string('passport_image_path');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events_registered');
    }
}; 