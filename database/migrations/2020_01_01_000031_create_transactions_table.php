<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->index('user_id');
            $table->integer('order_id')->nullable();
            $table->string('title')->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->integer('due_amount')->default(0);
            $table->integer('received_amount')->default(0);
            $table->string('payment_type')->nullable();
            $table->text('remark')->nullable();
            $table->integer('type')->default(0);
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
        Schema::dropIfExists('transactions');
    }
}