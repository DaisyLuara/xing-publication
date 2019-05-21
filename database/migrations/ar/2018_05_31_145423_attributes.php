<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Attributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('ar')->hasTable('xs_attributes')) {
            Schema::connection('ar')->create('xs_attributes', function (Blueprint $table) {
                $table->increments('id')->comment('属性配置');
                $table->integer('pid')->default(0)->comment('分类父节点');
                $table->string('name')->default('')->comment('属性名称');
                $table->string('desc')->default('')->comment('属性描述');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('ar')->dropIfExists('xs_attributes');
    }
}
