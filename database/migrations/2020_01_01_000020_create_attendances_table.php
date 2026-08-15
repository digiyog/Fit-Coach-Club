<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('franchise_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('weight_goal', 8, 2)->nullable();
            $table->string('weight_image')->nullable();
            $table->date('date')->nullable();
            $table->tinyInteger('type')->default(1)->comment('1-Absent, 2-Present');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}