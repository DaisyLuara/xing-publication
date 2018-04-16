<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WxUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wx_users', function (Blueprint $table) {

            $table->string('openid')->nullable();
            $table->tinyInteger('subscribe')->default(0);
            $table->string('nickname', 55)->default('');
            $table->tinyInteger('sex')->default(0)->comment('用户的性别，值为1时是男性，值为2时是女性，值为0时是未知');
            $table->string('language', 55)->default('');
            $table->string('city', 55)->default('');
            $table->string('province', 55)->default('');
            $table->string('country', 55)->default('');
            $table->string('headimgurl', 255)->default('');
            $table->integer('subscribe_time')->default(0);
            $table->string('unionid', 50)->default('')->comment('只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段');
            $table->string('remark', 50)->default('');
            $table->integer('groupid')->default(0);
            $table->string('authorizer_appid')->nullable();
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
