<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMerchantIdAndOutUidToCoupon extends Migration
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
            $table->integer('merchant_id')->default(0)->comment('商户ID');
            $table->string('out_uid')->default('')->comment('外部用户标识');
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
            $table->dropColumn('merchant_id');
            $table->dropColumn('out_uid');
        });
    }
}
