<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCouponBatchesWxUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->integer('wx_user_id')->default(0);
            $table->integer('taobao_user_id')->default(0);
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
            if (Schema::hasColumn('coupons', 'wx_user_id')) {
                $table->dropColumn('wx_user_id');
            }

            if (Schema::hasColumn('coupons', 'taobao_user_id')) {
                $table->dropColumn('taobao_user_id');
            }
        });
    }
}
