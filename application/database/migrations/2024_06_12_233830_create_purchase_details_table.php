<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_details', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_id');
            // $table->unsignedBigInteger('item_info_id');
            // $table->integer('unit_id');
            // $table->string('item_quantity')->nullable();
            $table->date('purchase_date');
            $table->string('purchased_by');
            // $table->unsignedBigInteger('warehouse_id');
            $table->decimal('total_price', 10, 2);

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            // $table->foreign('item_info_id')->references('id')->on('item_infos')->onDelete('cascade');
            // $table->foreign('unit_id')->references('unit_id')->on('units')->onDelete('cascade');
            // $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_details');
    }
}
