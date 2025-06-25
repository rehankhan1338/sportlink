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
            if (!Schema::hasColumn('academies', 'country')) {
                $table->string('country')->nullable()->after('name');
            }
            if (!Schema::hasColumn('academies', 'city')) {
                $table->string('city')->nullable()->after('country');
            }
            if (!Schema::hasColumn('academies', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('address');
            }
            if (!Schema::hasColumn('academies', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
            if (!Schema::hasColumn('academies', 'person_in_charge')) {
                $table->string('person_in_charge')->nullable()->after('longitude');
            }
            if (!Schema::hasColumn('academies', 'logo_path')) {
                $table->string('logo_path')->nullable()->after('website');
            }
            if (!Schema::hasColumn('academies', 'cover_path')) {
                $table->string('cover_path')->nullable()->after('logo_path');
            }
            if (!Schema::hasColumn('academies', 'affiliation')) {
                $table->string('affiliation')->nullable()->after('cover_path');
            }
            if (!Schema::hasColumn('academies', 'about')) {
                $table->text('about')->nullable()->after('affiliation');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academies', function (Blueprint $table) {
            $columns = [
                'country',
                'city',
                'latitude',
                'longitude',
                'person_in_charge',
                'logo_path',
                'cover_path',
                'affiliation',
                'about'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('academies', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
