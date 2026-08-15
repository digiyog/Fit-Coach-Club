<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBmiCalculatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bmi_calculator', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('age')->nullable();
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->string('gender')->nullable();
            $table->string('bmi')->nullable();
            $table->string('body_fat')->nullable();
            $table->string('visceral_fat')->nullable();
            $table->string('muscle_mass')->nullable();
            $table->string('metabolic_rate')->nullable();
            $table->string('biologic_age')->nullable();
            $table->string('body_age')->nullable();
            $table->integer('order')->default(0)->comment('0 for no order.');
            $table->tinyInteger('status')->default(1);
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('bmi_calculator');
    }
}