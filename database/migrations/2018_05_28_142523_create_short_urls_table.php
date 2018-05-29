<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShortUrlsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('short_urls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('target_url', 1024)->default('');
            $table->string('short_url', 171)->nullable();
            $table->integer('source')->default(0)->comment('0 内部调用生成 1 用户定义');
            $table->integer('url_type')->default(0)->comment('0 内部链接 1 跳转外部');
            $table->integer('tenant_id')->default(1);
            $table->integer('landing_record_id')->nullable();
            $table->timestamps();
            $table->string('description', 171)->nullable();
            $table->string('channel', 171)->nullable()->comment('渠道');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('short_urls');
    }

}
