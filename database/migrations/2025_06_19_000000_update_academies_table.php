<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First add new columns if they don't exist
        Schema::table('academies', function (Blueprint $table) {
            if (!Schema::hasColumn('academies', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('academies', 'logo')) {
                $table->string('logo')->nullable()->after('website');
            }
            if (!Schema::hasColumn('academies', 'cover_image')) {
                $table->string('cover_image')->nullable()->after('logo');
            }
            if (!Schema::hasColumn('academies', 'status')) {
                $table->string('status')->default('pending')->after('cover_image');
            }
        });

        // Copy data from old columns to new ones if they exist
        if (Schema::hasColumn('academies', 'about') && Schema::hasColumn('academies', 'description')) {
            DB::statement('UPDATE academies SET description = about WHERE description IS NULL');
        }
        if (Schema::hasColumn('academies', 'logo_path') && Schema::hasColumn('academies', 'logo')) {
            DB::statement('UPDATE academies SET logo = logo_path WHERE logo IS NULL');
        }
        if (Schema::hasColumn('academies', 'cover_path') && Schema::hasColumn('academies', 'cover_image')) {
            DB::statement('UPDATE academies SET cover_image = cover_path WHERE cover_image IS NULL');
        }

        // Create temporary column for affiliation_id if it doesn't exist
        if (!Schema::hasColumn('academies', 'affiliation_id')) {
            Schema::table('academies', function (Blueprint $table) {
                $table->unsignedBigInteger('affiliation_id')->nullable()->after('description');
            });

            // Update affiliation_id based on affiliation name if possible
            if (Schema::hasColumn('academies', 'affiliation')) {
                DB::statement('
                    UPDATE academies a
                    JOIN affiliations af ON a.affiliation = af.name
                    SET a.affiliation_id = af.id
                    WHERE a.affiliation_id IS NULL
                ');
            }

            // Now add the foreign key constraint
            Schema::table('academies', function (Blueprint $table) {
                $table->foreign('affiliation_id')->references('id')->on('affiliations')->onDelete('cascade');
            });
        }

        // Finally drop old columns if they exist
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

    public function down(): void
    {
        Schema::table('academies', function (Blueprint $table) {
            // First add back old columns if they don't exist
            if (!Schema::hasColumn('academies', 'country')) {
                $table->string('country')->nullable();
            }
            if (!Schema::hasColumn('academies', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('academies', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable();
            }
            if (!Schema::hasColumn('academies', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable();
            }
            if (!Schema::hasColumn('academies', 'person_in_charge')) {
                $table->string('person_in_charge')->nullable();
            }
            if (!Schema::hasColumn('academies', 'logo_path')) {
                $table->string('logo_path')->nullable();
            }
            if (!Schema::hasColumn('academies', 'cover_path')) {
                $table->string('cover_path')->nullable();
            }
            if (!Schema::hasColumn('academies', 'affiliation')) {
                $table->string('affiliation')->nullable();
            }
            if (!Schema::hasColumn('academies', 'about')) {
                $table->text('about')->nullable();
            }
        });

        // Copy data back to old columns
        if (Schema::hasColumn('academies', 'about') && Schema::hasColumn('academies', 'description')) {
            DB::statement('UPDATE academies SET about = description WHERE about IS NULL');
        }
        if (Schema::hasColumn('academies', 'logo_path') && Schema::hasColumn('academies', 'logo')) {
            DB::statement('UPDATE academies SET logo_path = logo WHERE logo_path IS NULL');
        }
        if (Schema::hasColumn('academies', 'cover_path') && Schema::hasColumn('academies', 'cover_image')) {
            DB::statement('UPDATE academies SET cover_path = cover_image WHERE cover_path IS NULL');
        }

        // Copy affiliation name back if possible
        if (Schema::hasColumn('academies', 'affiliation') && Schema::hasColumn('academies', 'affiliation_id')) {
            DB::statement('
                UPDATE academies a
                JOIN affiliations af ON a.affiliation_id = af.id
                SET a.affiliation = af.name
                WHERE a.affiliation IS NULL
            ');
        }

        // Drop foreign key constraint and new columns if they exist
        Schema::table('academies', function (Blueprint $table) {
            if (Schema::hasColumn('academies', 'affiliation_id')) {
                $table->dropForeign(['affiliation_id']);
                $table->dropColumn('affiliation_id');
            }
            if (Schema::hasColumn('academies', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('academies', 'logo')) {
                $table->dropColumn('logo');
            }
            if (Schema::hasColumn('academies', 'cover_image')) {
                $table->dropColumn('cover_image');
            }
            if (Schema::hasColumn('academies', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
}; 