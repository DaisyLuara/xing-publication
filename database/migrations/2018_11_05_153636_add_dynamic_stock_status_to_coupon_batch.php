<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDynamicStockStatusToCouponBatch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_batches', function (Blueprint $table) {
            $table->tinyInteger('dynamic_stock_status')->default(0)->comment('是否计算 动态库存 0:否 1: 是');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupon_batches', function (Blueprint $table) {
            if (Schema::hasColumn('coupon_batches', 'dynamic_stock_status')) {
                $table->dropColumn('dynamic_stock_status');
            }
        });
    }
}
