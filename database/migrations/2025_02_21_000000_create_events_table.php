<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('location');
            $table->string('country');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('last_date_of_registration')->nullable();
            $table->string('timezone');
            $table->string('status');
            $table->string('visibility');
            $table->text('description')->nullable();
            $table->foreignId('game_id')->nullable()->constrained('games');
            $table->string('type')->nullable();
            $table->text('rules')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->float('adult_price')->nullable();
            $table->float('minor_price')->nullable();
            $table->float('children_price')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
}; 