<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLocationProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('erp_location_products', function (Blueprint $table) {
            $table->dropColumn('product_sku');
            $table->integer('product_id')->after('location_id')->nullable()->comment('产品ID');
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
            $table->string('product_sku')->nullable()->comment('产品sku');
        });
    }
}
