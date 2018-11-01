<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTitleToCouponBatch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_batches', function (Blueprint $table) {
            $table->string('title')->default('')->comment('标题');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('coupons', function (Blueprint $table) {
            if (Schema::hasColumn('coupon_batches', 'title')) {
                $table->dropColumn('title');
            }
        });
    }
}
