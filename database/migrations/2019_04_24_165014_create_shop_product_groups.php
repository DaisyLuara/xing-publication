<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopProductGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_product_groups', static function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sku_id');
            $table->integer('groups_id');
        });

        Schema::create('shop_groups', static function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('package_id')->comment('所属套餐');
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
        Schema::dropIfExists('shop_product_groups');
        Schema::dropIfExists('shop_groups');
    }
}
