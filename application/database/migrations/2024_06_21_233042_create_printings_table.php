<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('printings', function (Blueprint $table) {
            $table->id();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('cutting_id');
            $table->string('status')->default(0);
            $table->string('created_by')->nullable();
            $table->string('output_name')->nullable();
            $table->string('output_quantity')->nullable();
            $table->string('output_actual_quantity')->nullable();
            $table->string('output_loss_quantity')->nullable();
            $table->string('output_found_quantity')->nullable();
            $table->string('output_damaged_quantity')->nullable();
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('cutting_id')->references('id')->on('cuttings')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('printings');
    }
}
