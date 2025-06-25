<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('events_registered', function (Blueprint $table) {
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->onDelete('set null');
            $table->foreignId('profile_id')->nullable()->constrained('profiles')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('events_registered', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropForeign(['profile_id']);
            $table->dropColumn(['organization_id', 'profile_id']);
        });
    }
}; 