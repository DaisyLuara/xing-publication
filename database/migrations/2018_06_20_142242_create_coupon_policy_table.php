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
            $table->integer('create_user_id');
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
            $table->enum('gender', ['male', 'female', 'none'])->default('none');
            $table->integer('rate')->default(0);
            $table->enum('type', ['age', 'gender', 'rate', 'mix'])->default('age')->comment('类型');
            $table->timestamps();

            $table->unique(['policy_id', 'coupon_batch_id']);
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
