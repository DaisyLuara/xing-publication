<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBelongToUserCouponBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_coupon_batches', function (Blueprint $table) {
            $table->string('belong')->nullable()->after('coupon_batch_id')->comment('节目版本名称');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_coupon_batches', function (Blueprint $table) {
            $table->dropColumn('belong');
        });
    }
}
