<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSplashAndRatingToAppConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('app_configs')) {
            Schema::table('app_configs', function (Blueprint $table) {
                if (!Schema::hasColumn('app_configs', 'splash_video_enabled')) {
                    $table->boolean('splash_video_enabled')->default(0)->after('ios_store_url')->comment('1 = Video Splash Enabled, 0 = Normal Splash');
                }
                if (!Schema::hasColumn('app_configs', 'splash_video_url')) {
                    $table->text('splash_video_url')->nullable()->after('splash_video_enabled');
                }
                if (!Schema::hasColumn('app_configs', 'splash_video_start_date')) {
                    $table->date('splash_video_start_date')->nullable()->after('splash_video_url');
                }
                if (!Schema::hasColumn('app_configs', 'splash_video_end_date')) {
                    $table->date('splash_video_end_date')->nullable()->after('splash_video_start_date');
                }
                if (!Schema::hasColumn('app_configs', 'rating_required')) {
                    $table->boolean('rating_required')->default(1)->after('splash_video_end_date')->comment('1 = Rating Popup Required, 0 = Disabled');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('app_configs')) {
            Schema::table('app_configs', function (Blueprint $table) {
                $columns = [
                    'splash_video_enabled',
                    'splash_video_url',
                    'splash_video_start_date',
                    'splash_video_end_date',
                    'rating_required',
                ];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('app_configs', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
}

