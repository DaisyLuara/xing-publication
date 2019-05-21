<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopProductPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('')->comment('套餐名称');
            $table->string('description')->default('描述');
            $table->integer('count')->default(0)->comment('购买次数');
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
        Schema::dropIfExists('shop_product_packages');
    }
}
