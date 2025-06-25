<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Update existing round_number values to match round_type
        DB::statement("
            UPDATE matches 
            SET round_type = CASE
                WHEN round_number = 1 THEN 'semifinal'
                WHEN round_number = 2 AND bracket_type = 'single_elimination' THEN 'bronze'
                WHEN (round_number = 3 AND bracket_type = 'single_elimination') OR 
                     (round_number = 2 AND bracket_type != 'single_elimination') THEN 'final'
                ELSE CONCAT('round_', round_number)
            END
        ");
    }

    public function down()
    {
        // No need for down migration as we're just updating existing data
    }
}; 