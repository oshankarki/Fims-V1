<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategorIdToItemInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_infos', function (Blueprint $table) {
            $table->unsignedBigInteger('item_category_id')->after('id');
            $table->foreign('item_category_id')
                ->references('id')
                ->on('item_categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_infos', function (Blueprint $table) {
            $table->dropForeign(['item_category_id']); // Drop the foreign key constraint
            $table->dropColumn('item_category_id'); // Drop the column
        });
    }
}
