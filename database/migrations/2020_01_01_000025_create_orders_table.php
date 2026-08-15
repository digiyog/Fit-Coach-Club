<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('franchise_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->integer('order_id')->default(0);
            $table->string('order_number')->nullable();
            $table->date('order_date')->nullable();
            $table->integer('product_quantity')->nullable();
            $table->decimal('total_amount', 9, 2)->nullable();
            $table->integer('discount')->nullable();
            $table->decimal('net_amount', 9, 2)->nullable();
            $table->string('user_name')->nullable();
            $table->string('mobile_number')->nullable();
            $table->tinyInteger('payment_mode')->default(1)->comment('1:COD, 2:Online, 3:Other');
            $table->tinyInteger('payment_status')->default(1)->comment('1- Pending 2- Success 3- Failed');
            $table->tinyInteger('order_status')->default(1)->comment('1.Order Placed 2. Ready to ship 4. Shipped 5. In Transit 6. Delivered 7. Cancelled 8. Refund');
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
        Schema::dropIfExists('orders');
    }
}