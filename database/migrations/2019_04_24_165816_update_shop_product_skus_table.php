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
            $table->string('type')->default('project')->comment('类型: project-节目,skin-皮肤 后续会扩展其他');
            $table->integer('credit_price')->default(0)->comment('积分价格');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('policies', static function (Blueprint $table) {
            $table->dropColumn('piid');
            $table->dropColumn('bid');
            $table->dropColumn('type');
            $table->dropColumn('credit_price');
        });
    }
}
