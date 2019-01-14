<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterWarehouseChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('erp_warehouse_changes', function (Blueprint $table) {
            $table->dropColumn('sku');
            $table->integer('product_id')->after('id')->nullable()->comment('产品ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('erp_warehouse_changes', function (Blueprint $table) {
            $table->string('sku')->nullable()->comment('产品SKU');
        });
    }
}
