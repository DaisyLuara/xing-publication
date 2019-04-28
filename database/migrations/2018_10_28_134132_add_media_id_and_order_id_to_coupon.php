<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMediaIdAndOrderIdToCoupon extends Migration
{
    /**
     * /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('order_no', 64)->default('')->comment('订单编号');
            $table->string('order_total', 64)->default('')->comment('订单编号');
            $table->integer('media_id')->default(0)->comment('附件');
            $table->integer('shop_customer_id')->default(0)->comment('核销人员ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('order_no');
            $table->dropColumn('media_id');
            $table->dropColumn('shop_customer_id');
        });
    }
}
