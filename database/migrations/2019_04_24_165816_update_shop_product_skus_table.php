<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateShopProductSkusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_product_skus', static function (Blueprint $table) {
            $table->integer('piid')->default(0)->comment('节目');
            $table->integer('bid')->default(0)->comment('皮肤');
            $table->integer('oid')->default(0)->comment('点位');
            $table->json('point_data')->nullable()->comment('点位信息');
            $table->string('type')->default('project')->comment('类型: project-节目,skin-皮肤 后续会扩展其他');
            $table->integer('credit_price')->default(0)->comment('积分价格');
        });


        Schema::table('shop_products', static function (Blueprint $table) {
            $table->string('landing_type')->default('')->comment('落地方式');
            $table->string('device')->default('支持运行设备');
            $table->string('interactive_tech')->default('')->comment('互动技术');
            $table->string('play')->default('')->comment('玩法类型');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_product_skus', static function (Blueprint $table) {
            $table->dropColumn('piid');
            $table->dropColumn('bid');
            $table->dropColumn('oid');
            $table->dropColumn('point_data');
            $table->dropColumn('type');
            $table->dropColumn('credit_price');
        });

        Schema::table('shop_products', static function (Blueprint $table) {
            $table->dropColumn('landing_type');
            $table->dropColumn('device');
            $table->dropColumn('interactive_tech');
            $table->dropColumn('play');
        });
    }
}
