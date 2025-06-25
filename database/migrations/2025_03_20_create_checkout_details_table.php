<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('checkout_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('profile_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('academy_id')->nullable()->default(0);
            $table->unsignedBigInteger('division_id')->nullable();
            $table->string('academy_name');
            $table->decimal('amount', 10, 2);
            $table->string('user_type');
            $table->string('payment_status');
            $table->string('stripe_session_id')->nullable();
            $table->json('payment_details')->nullable();
            $table->timestamps();

            // Add foreign key that only applies when academy_id is not 0
            $table->foreign('academy_id')
                  ->references('id')
                  ->on('academies')
                  ->onDelete('cascade');
        });

        // Add a check constraint to ensure academy_id is either 0 or exists in academies table
        DB::statement('ALTER TABLE checkout_details ADD CONSTRAINT check_academy_id CHECK (academy_id >= 0)');
    }

    public function down()
    {
        Schema::dropIfExists('checkout_details');
    }
}; 