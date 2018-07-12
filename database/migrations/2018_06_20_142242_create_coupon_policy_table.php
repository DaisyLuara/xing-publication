<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponPolicyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('create_user_id')->comment('创建人');
            $table->integer('bd_user_id')->comment('关联BD');
            $table->string('name')->default('')->comment('投放策略');
            $table->string('desc', 1024)->default('')->comment('描述');
            $table->timestamps();
        });

        Schema::create('coupon_batch_policy', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('policy_id');
            $table->integer('coupon_batch_id');
            $table->integer('min_age')->default(0);
            $table->integer('max_age')->default(0);
            $table->tinyInteger('gender')->default(0)->comment('1:女 0:男');
            $table->integer('rate')->default(100);
            $table->enum('type', ['age', 'gender', 'rate', 'mix'])->default('age')->comment('类型');
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
        Schema::dropIfExists('coupon_batch_policy');
        Schema::dropIfExists('policies');
    }
}
