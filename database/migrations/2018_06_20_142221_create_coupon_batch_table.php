<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponBatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_batches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('coupon_policy_id');
            $table->integer('company_id');
            $table->integer('user_id');
            $table->string('image');
            $table->integer('count')->comment('总数');
            $table->integer('stock')->comment('库存');
            $table->integer('people_max_get')->comment('每人最大获取数');
            $table->tinyInteger('pmg_status')->default(0)->comment('是否开启每人无限领取,1:开启,0:关闭');
            $table->integer('day_max_get')->comment('每天最大获取数');
            $table->tinyInteger('dmg_status')->comment('是否开启每天无限领取,1:开启,0:关闭');
            $table->integer('effective_day')->comment('有效天数');
            $table->timestamp('start_date')->comment('开始日期')->nullable();
            $table->timestamp('end_date')->comment('结束日期')->nullable();
            $table->tinyInteger('is_active')->default(1)->comment('1 启用，0 停用');
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
        Schema::dropIfExists('coupon_batches');
    }
}
