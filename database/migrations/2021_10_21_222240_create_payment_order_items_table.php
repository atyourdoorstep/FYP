<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_order_items', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_payment_id')->nullable();
            $table->string('type')->default('cash on delivery');
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('order_item_id');

            $table->index('order_item_id');
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
        Schema::dropIfExists('payment_order_items');
    }
}
