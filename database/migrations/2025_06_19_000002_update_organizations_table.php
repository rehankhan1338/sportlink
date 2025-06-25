<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            // Add new columns if they don't exist
            if (!Schema::hasColumn('organizations', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('organizations', 'website')) {
                $table->string('website')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('organizations', 'type')) {
                $table->string('type')->default('business')->after('website');
            }
            if (!Schema::hasColumn('organizations', 'status')) {
                $table->string('status')->default('pending')->after('type');
            }

            // Drop old columns if they exist
            $columnsToRemove = ['company', 'first_name', 'last_name', 'zip', 'card_number'];
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('organizations', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Make email required if it exists
            if (Schema::hasColumn('organizations', 'email')) {
                $table->string('email')->nullable(false)->change();
            }

            // Add foreign key constraint to existing user_id column if it exists
            if (Schema::hasColumn('organizations', 'user_id')) {
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist, continue
                }
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            // Add back old columns if they don't exist
            if (!Schema::hasColumn('organizations', 'company')) {
                $table->string('company')->nullable();
            }
            if (!Schema::hasColumn('organizations', 'first_name')) {
                $table->string('first_name')->nullable();
            }
            if (!Schema::hasColumn('organizations', 'last_name')) {
                $table->string('last_name')->nullable();
            }
            if (!Schema::hasColumn('organizations', 'zip')) {
                $table->string('zip')->nullable();
            }
            if (!Schema::hasColumn('organizations', 'card_number')) {
                $table->string('card_number')->nullable();
            }

            // Drop new columns if they exist
            $columnsToRemove = ['description', 'website', 'type', 'status'];
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('organizations', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Make email nullable again if it exists
            if (Schema::hasColumn('organizations', 'email')) {
                $table->string('email')->nullable()->change();
            }

            // Remove the foreign key constraint if it exists
            if (Schema::hasColumn('organizations', 'user_id')) {
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist, continue
                }
            }
        });
    }
}; 