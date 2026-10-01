<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('coaches')) {
            Schema::create('coaches', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('franchise_id')->nullable()->index();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('mobile_number')->nullable();
                $table->string('profile_image')->nullable();
                $table->string('specialization')->nullable();
                $table->string('experience_years')->nullable();
                $table->text('bio')->nullable();
                $table->tinyInteger('status')->default(1)->comment('1 = Active, 0 = Inactive');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
                $table->softDeletes();
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
        Schema::dropIfExists('coaches');
    }
}
