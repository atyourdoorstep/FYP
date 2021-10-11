<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
//            $table->string('item_code')->unique();
            $table->string('image');
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('seller_id');
            $table->string('type')->default('product');
            $table->boolean('isBargainAble')->default(false);
            $table->boolean('inStock')->default(true);
            $table->index('category_id');
            $table->index('seller_id');
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
        Schema::dropIfExists('items');
    }
}
