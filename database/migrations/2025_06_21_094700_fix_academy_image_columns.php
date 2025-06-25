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
        Schema::table('academies', function (Blueprint $table) {
            // Drop the old columns if they exist
            if (Schema::hasColumn('academies', 'logo_path')) {
                $table->dropColumn('logo_path');
            }
            if (Schema::hasColumn('academies', 'cover_path')) {
                $table->dropColumn('cover_path');
            }

            // Add the new columns if they don't exist
            if (!Schema::hasColumn('academies', 'logo')) {
                $table->string('logo')->nullable();
            }
            if (!Schema::hasColumn('academies', 'cover_image')) {
                $table->string('cover_image')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academies', function (Blueprint $table) {
            // Drop the new columns if they exist
            if (Schema::hasColumn('academies', 'logo')) {
                $table->dropColumn('logo');
            }
            if (Schema::hasColumn('academies', 'cover_image')) {
                $table->dropColumn('cover_image');
            }

            // Add back the old columns
            $table->string('logo_path')->nullable();
            $table->string('cover_path')->nullable();
        });
    }
}; 