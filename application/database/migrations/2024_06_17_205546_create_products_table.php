<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description');
            $table->string('price');
            $table->integer('quantity')->unsigned()->default(0);
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('image')->nullable();
            $table->string('variant')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->integer('unit_id')->nullable();
            $table->integer('actual_quantity')->default(0)->nullable();
            $table->integer('stock_a')->default(0)->nullable();
            $table->integer('stock_b')->default(0)->nullable();

            
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('unit_id')->references('unit_id')->on('units');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
