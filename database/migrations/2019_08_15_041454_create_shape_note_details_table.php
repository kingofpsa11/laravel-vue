<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShapeNoteDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shape_note_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shape_note_id');
            $table->foreign('shape_note_id')->references('id')->on('shape_notes')->onDelete('cascade');
            $table->unsignedBigInteger('contract_detail_id');
            $table->foreign('contract_detail_id')->references('id')->on('contract_details')->onDelete('cascade');
            $table->unsignedBigInteger('manufacturer_note_detail_id');
            $table->foreign('manufacturer_note_detail_id')->references('id')->on('manufacturer_note_details')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('bom_id');
            $table->foreign('bom_id')->references('id')->on('boms')->onDelete('cascade');
            $table->float('bom_detail_quantity', 10, 3);
            $table->float('quantity', 10, 3);
            $table->integer('status');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('shape_note_details');
    }
}
