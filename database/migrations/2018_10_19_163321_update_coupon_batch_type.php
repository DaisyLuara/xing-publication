<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCouponBatchType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_batches', function (Blueprint $table) {
            $table->tinyInteger('type')->default(1);
            $table->string('redirect_url')->default('');
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
            if (Schema::hasColumn('coupon_batches', 'redirect_url')) {
                $table->dropColumn('redirect_url');
            }

            if (Schema::hasColumn('coupon_batches', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
}
