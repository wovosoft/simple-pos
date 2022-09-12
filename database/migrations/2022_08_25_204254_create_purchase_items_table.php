<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("product_id");
            //nullable, when not items are received.
            //when atleast one item is received, product item should be created automatically
            $table->unsignedBigInteger("product_item_id")->nullable();
            $table->unsignedBigInteger("purchase_id");
            $table->unsignedBigInteger("quantity")->default(0);
            //should be equal to product item quantity
            $table->unsignedBigInteger("received_quantity")->default(0);    
            $table->unsignedBigInteger("cost")->default(0);
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
        Schema::dropIfExists('purchase_items');
    }
};
