<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWxCouponBatchIdToCouponBatch extends Migration
{
    /**
     * /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_batches', function (Blueprint $table) {
            $table->integer('wechat_coupon_batch_id')->default(0)->comment('微信卡券ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn("coupon_batches", "wx_coupon_batch_id")) {
            Schema::table('coupon_batches', function (Blueprint $table) {
                $table->dropColumn('wx_coupon_batch_id');
            });
        }
    }
}
