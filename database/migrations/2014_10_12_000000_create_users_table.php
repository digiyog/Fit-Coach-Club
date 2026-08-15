<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_type')->nullable()->comment('Demo User, 3 Days trail, Regular User');
            $table->string('user_state')->nullable()->comment('Online, Offline');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('country_code', 40)->nullable();
            $table->string('mobile_number')->nullable();
            $table->timestamp('mobile_number_verified_at')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('password')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('qr_code')->nullable();
            $table->string('coach_name')->nullable();
            $table->integer('meal_type_id')->nullable();
            $table->integer('product_type_id')->default(1);
            $table->integer('days')->default(0);
            $table->unsignedBigInteger('role_id')->nullable();
            $table->string('role_type')->nullable();
            $table->integer('due_amount')->default(0);
            $table->integer('age')->nullable();
            $table->string('height')->nullable();
            $table->integer('gender')->nullable()->comment('1 - Male 2 - Female');
            $table->string('starting_weight')->nullable();
            $table->string('current_weight')->nullable();
            $table->string('weight_goal')->nullable();
            $table->string('discount')->nullable();
            $table->dateTime('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->string('uuid')->nullable();
            $table->text('fcm_token')->nullable();
            $table->string('device_id')->nullable();
            $table->string('device_type')->nullable();
            $table->string('device_os')->nullable();
            $table->string('device_os_version')->nullable();
            $table->string('device_manufacturer')->nullable();
            $table->string('device_model')->nullable();
            $table->string('app_version')->nullable();
            $table->date('start_date')->useCurrent();
            $table->date('end_date')->useCurrent();
            $table->tinyInteger('status')->default(1)->comment('1 = Active, 0 = Inactive');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}