<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterErpLocationProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('erp_location_products', function (Blueprint $table) {
            $table->integer('stock')->default(0)->comment('库存数量')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('erp_location_products', function (Blueprint $table) {
            $table->integer('stock')->nullable()->comment('库存数量')->change();
        });
    }
}
