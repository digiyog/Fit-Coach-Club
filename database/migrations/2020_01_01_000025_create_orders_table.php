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
            $table->unsignedBigInteger('user_id')->nullable()->index('product_orders_user_id_foreign');
            $table->string('order_number')->nullable();
            $table->string('currency')->default('INR');
            $table->decimal('total_amount', 9, 2)->default(0.00);
            $table->decimal('tax_amount', 9, 2)->default(0.00);
            $table->decimal('net_amount', 9, 2)->default(0.00);
            $table->decimal('discount', 9, 2)->default(0.00);
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile_number', 50)->nullable();
            $table->text('address')->nullable();
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