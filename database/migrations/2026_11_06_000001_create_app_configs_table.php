<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('app_configs')) {
            Schema::create('app_configs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('popup_title')->nullable();
                $table->text('popup_message')->nullable();
                $table->boolean('popup_status')->default(1)->comment('1 = ON, 0 = OFF');
                $table->string('android_latest_version', 50)->default('1.0.0');
                $table->boolean('android_force_update')->default(0)->comment('1 = Force Update, 0 = Optional');
                $table->text('android_store_url')->nullable();
                $table->string('ios_latest_version', 50)->default('1.0.0');
                $table->boolean('ios_force_update')->default(0)->comment('1 = Force Update, 0 = Optional');
                $table->text('ios_store_url')->nullable();
                $table->boolean('splash_video_enabled')->default(0)->comment('1 = Video Splash Enabled, 0 = Normal Splash');
                $table->text('splash_video_url')->nullable();
                $table->date('splash_video_start_date')->nullable();
                $table->date('splash_video_end_date')->nullable();
                $table->boolean('rating_required')->default(1)->comment('1 = Rating Popup Required, 0 = Disabled');
                $table->timestamps();
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
        Schema::dropIfExists('app_configs');
    }
}

