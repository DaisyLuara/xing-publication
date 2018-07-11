<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('coupon_batch_id');
            $table->string('mobile', 20);
            $table->string('code')->default('');
            $table->string('picm_id', 64)->default('')->comment('第三方 投放ID');
            $table->string('trace_id', 64)->default('')->comment('第三方 追踪ID');
            $table->tinyInteger('status')->default(0)->comment('0 未领取, 1 已使用, 2 停用, 3 未使用');
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
        Schema::dropIfExists('coupons');
    }
}
