<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RedpackBill extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redpack_bill', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('coupon_batch_id')->default(0)->comment('优惠券规则配置');
            $table->string('coupon_code')->default('')->comment('优惠券code');
            $table->string('mch_billno', 32)->default('')->comment('商户订单号');
            $table->string('mch_id', 32)->default('')->comment('微信支付分配的商户号');
            $table->string('wxappid', 32)->default('')->comment('微信分配的公众账号ID');
            $table->string('send_name', 32)->default('')->comment('红包发送者名称');
            $table->string('re_openid', 32)->default('')->comment('接受红包的用户openid');
            $table->integer('total_amount')->default(0)->comment('付款金额，单位分');
            $table->integer('total_num')->default(1)->comment('接受红包的用户openid');
            $table->string('scene_id', 32)->default('')->comment('发放红包使用场景，红包金额大于200或者小于1元时必传');
            $table->string('return_code', 16)->default('')->comment('SUCCESS/FAIL');
            $table->string('return_msg', 128)->default('')->comment('返回信息，如非空，为错误原因');
            $table->string('result_code', 16)->default('')->comment('当状态为FAIL时，存在业务结果未明确的情况');
            $table->string('err_code', 32)->default('')->comment('错误码信息');
            $table->string('err_code_des', 128)->default('')->comment('结果信息描述');
            $table->string('send_listid', 128)->default('')->comment('红包订单的微信单号');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('redpack_bill');
    }
}
