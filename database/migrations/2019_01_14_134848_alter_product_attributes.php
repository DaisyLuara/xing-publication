<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('erp_product_attributes', function (Blueprint $table) {
            $table->dropColumn('product_sku');
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
        Schema::table('erp_product_attributes', function (Blueprint $table) {
            $table->string('product_sku')->nullable()->comment('产品SKU');
        });
    }
}
