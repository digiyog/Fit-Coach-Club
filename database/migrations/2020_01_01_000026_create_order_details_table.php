<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index('product_order_details_product_order_id_foreign');
            $table->unsignedBigInteger('product_id')->nullable()->index('product_order_details_product_id_foreign');
            $table->string('name')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('image')->nullable();
            $table->decimal('price', 9, 2)->nullable();
            $table->decimal('net_amount', 9, 2)->nullable();
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
        Schema::dropIfExists('order_details');
    }
}