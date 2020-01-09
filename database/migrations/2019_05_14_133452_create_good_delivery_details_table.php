<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodDeliveryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('good_delivery_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('good_delivery_id');
            $table->foreign('good_delivery_id')->references('id')->on('good_deliveries')->onDelete('cascade');
            $table->morphs('deliverable');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->float('quantity', 8 ,3);
            $table->float('actual_quantity', 8, 3)->default(0);
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->integer('status');
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
        Schema::dropIfExists('good_delivery_details');
    }
}
