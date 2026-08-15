<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0)->nullable();
            $table->integer('sender_id')->default(0)->nullable();
            $table->integer('data_id')->nullable();
            $table->tinyInteger('notification_type')->default(1)->comment('Notification Type = 1 - Days Added 2 - 10 Days Left 3 - 5 Days Left 4 - 1 Days Left 5 - Attendence 6 - Meal Time 7 - Water Notification 8 - Achievement 9 - Announcement 10 - Pending Amount');
            $table->string('notification_title')->nullable();
            $table->mediumText('notification_text')->nullable();
            $table->string('sender_name')->nullable();
            $table->string('receiver_name')->nullable();
            $table->integer('sent_status')->default(1);
            $table->tinyInteger('status')->default(0)->comment('0 Unread , 1 read, 2 delete');
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
        Schema::dropIfExists('notifications');
    }
}