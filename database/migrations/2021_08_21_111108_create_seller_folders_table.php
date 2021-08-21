<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_folders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id')->unique();
            $table->string('main');
            $table->string('item');
            $table->string('invoice');
            $table->string('return_invoice');

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
        Schema::dropIfExists('seller_folders');
    }
}
