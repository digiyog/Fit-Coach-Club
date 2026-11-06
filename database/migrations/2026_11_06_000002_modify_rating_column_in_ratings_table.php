<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyRatingColumnInRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('ratings') && Schema::hasColumn('ratings', 'rating')) {
            try {
                DB::statement("ALTER TABLE `ratings` MODIFY `rating` DECIMAL(3,1) DEFAULT 1.0 NULL");
            } catch (\Throwable $e) {
                // Log exception silently if already modified or direct SQL preferred
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('ratings') && Schema::hasColumn('ratings', 'rating')) {
            try {
                DB::statement("ALTER TABLE `ratings` MODIFY `rating` TINYINT DEFAULT 1 NULL");
            } catch (\Throwable $e) {
            }
        }
    }
}

