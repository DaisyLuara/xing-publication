<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Companies extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('name', 1024)->defualt('')->comment('公司全称');
            $table->string('address', 1024)->defualt('')->comment('公司地址');
            $table->enum('status', [1, 2, 3, 4])->default(1)->comment('1待审核 2.待合作 3合作中 4已结束');
            $table->integer('trade_id')->unsigned()->default(0)->index();
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
        Schema::dropIfExists('companies');
    }
}
