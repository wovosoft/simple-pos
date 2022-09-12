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
        /**
         * Quantity field should be only changed during purchase.
         * That means, quantity is the total number of purchased quantity.
         * when sold, the sold_quantity will be increased. 
         * when damanged, the damanged_quantity will be increased
         * when returned to spplier the returned_quantity will be increased
         * 
         */
        Schema::create('product_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("product_id");
            $table->double("cost");         //original purchase cost
            $table->double("quantity");     //items of same cost during the same purchase
            $table->double("sold_quantity")->default(0);
            $table->double("damaged_quantity")->default(0);
            $table->double("returned_quantity")->default(0);
            //available: calculated(quantity - sold_quantity - damanged_quantity - returned_quantity)
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
        Schema::dropIfExists('product_items');
    }
};
