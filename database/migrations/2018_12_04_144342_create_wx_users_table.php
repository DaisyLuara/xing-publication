<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWxUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wx_users', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('subscribe')->default(0);
            $table->string('nickname')->default('');
            $table->string('username')->nullable()->comment('用户姓名');
            $table->string('avatar')->nullable();
            $table->integer('age')->nullable();
            $table->tinyInteger('gender')->default(0)->comment('用户的性别，值为1时是男性，值为2时是女性，值为0时是未知');
            $table->string('openid')->unique()->nullable();
            $table->string('mobile')->unique()->nullable();
            $table->string('mallcoo_open_user_id')->nullable()->comment('猫酷 OpenUserId');
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
        Schema::dropIfExists('wx_users');
    }
}
