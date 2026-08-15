<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFranchiseMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_memberships', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('franchise_id')->nullable();
            $table->integer('membership_id')->nullable();
            $table->integer('payment_status')->default(1)->comment('1 - Pending 2 - Completed');
            $table->integer('total_amount')->default(0);
            $table->integer('received_amount')->default(0);
            $table->integer('pending_amount')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('remark')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('franchise_memberships');
    }
}