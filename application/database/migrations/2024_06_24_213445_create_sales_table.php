<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id');
            $table->date('sales_date');
            $table->integer('staff_id');
            $table->integer('sales_quantity')->nullable();
            $table->string('contact_phone')->nullable();
            // $table->unsignedBigInteger('product_id');
            $table->foreign('staff_id')->references('id')->on('users');
            $table->foreign('customer_id')->references('id')->on('customers');
            // $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            // $table->foreign('manufacture_product_id')->references('id')->on('manufacture_products');
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
        Schema::dropIfExists('sales');
    }
}
