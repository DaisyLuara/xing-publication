<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketPointCouponBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_point_coupon_batches', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('coupon_batch_id');
            $table->unsignedInteger('marketid')->comment('商场ID');
            $table->unsignedInteger('oid')->nullable()->comment('门店ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('market_point_coupon_batches');
    }
}
