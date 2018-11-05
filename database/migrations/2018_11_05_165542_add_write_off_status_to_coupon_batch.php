<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWriteOffStatusToCouponBatch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_batches', function (Blueprint $table) {
            $table->tinyInteger('write_off_status')->default(1)->comment('是否是系统核销  0:否 1: 是');
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
            if (Schema::hasColumn('coupon_batches', 'write_off_status')) {
                $table->dropColumn('write_off_status');
            }
        });
    }
}
