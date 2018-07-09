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
            $table->integer('company_id');
            $table->integer('create_user_id')->comment('创建人ID');
            $table->string('image_url')->default('');
            $table->string('name')->default('');
            $table->string('description')->default('');
            $table->integer('amount')->comment('金额')->default(0);
            $table->integer('count')->comment('库存总数')->default(0);
            $table->integer('stock')->comment('剩余库存')->default(0);
            $table->integer('people_max_get')->comment('每人最大获取数')->default(0);
            $table->tinyInteger('pmg_status')->comment('是否开启每人无限领取,1:开启,0:关闭')->default(0);
            $table->integer('day_max_get')->comment('每天最大获取数')->default(0);
            $table->tinyInteger('dmg_status')->comment('是否开启每天无限领取,1:开启,0:关闭')->default(0);
            $table->tinyInteger('is_fixed_date')->comment('是否固定日期,1:固定,0:不固定')->default(0);
            $table->integer('delay_effective_day')->comment('延后生效天数')->default(0);
            $table->integer('effective_day')->comment('有效天数')->default(0);
            $table->timestamp('start_date')->comment('开始日期')->nullable();
            $table->timestamp('end_date')->comment('结束日期')->nullable();
            $table->enum('is_active', [0, 1])->default(1)->comment('1 启用,0 停用');
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
