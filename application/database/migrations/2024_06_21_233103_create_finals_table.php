<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finals', function (Blueprint $table) {
            $table->id();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('stiching_id');
            $table->string('status')->default(0);
            $table->string('created_by')->nullable();
            $table->string('output_name')->nullable();
            $table->string('output_quantity')->nullable();
            $table->string('output_actual_quantity')->nullable();
            $table->string('output_loss_quantity')->default(0);
            $table->string('output_found_quantity')->default(0);
            $table->string('output_damaged_quantity')->default(0);
            $table->string('actual_quality_A')->nullable();
            $table->string('actual_quality_B')->nullable();
            $table->string('quality_A_price')->nullable();
            $table->string('quality_B_price')->nullable();
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('stiching_id')->references('id')->on('stichings')->onDelete('cascade');
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
        Schema::dropIfExists('finals');
    }
}
