<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMallcooConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mallcoo_config', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('marketid');
            $table->string('mallcoo_mall_id')->default('');
            $table->string('mallcoo_appid')->default('');
            $table->string('mallcoo_public_key')->default('')->comment('公钥');
            $table->string('mallcoo_private_key')->default('')->comment('私钥');
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
        Schema::dropIfExists('mallcoo_config');
    }
}
