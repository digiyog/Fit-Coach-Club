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
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('mobile_number', 50)->nullable();
            $table->integer('age')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->string('gender', 20)->nullable();
            $table->decimal('bmi', 8, 2)->nullable();
            $table->decimal('body_fat', 8, 2)->nullable();
            $table->decimal('visceral_fat', 8, 2)->nullable();
            $table->decimal('muscle_mass', 8, 2)->nullable();
            $table->decimal('metabolic_rate', 8, 2)->nullable();
            $table->integer('biologic_age')->nullable();
            $table->integer('body_age')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
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