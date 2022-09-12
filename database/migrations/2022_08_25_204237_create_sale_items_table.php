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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("product_id");
            $table->unsignedBigInteger("product_item_id");
            $table->unsignedBigInteger("sale_id");
            $table->unsignedBigInteger("quantity")->default(0); //ordered quantity
            $table->unsignedBigInteger("delivered_quantity")->default(0);  ///imapcts stock of product item
            $table->unsignedBigInteger("price")->default(0);
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
        Schema::dropIfExists('sale_items');
    }
};
