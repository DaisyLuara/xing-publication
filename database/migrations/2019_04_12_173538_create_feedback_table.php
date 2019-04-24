<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable()->comment("标题");
            $table->text('content')->nullable()->comment("内容");
            $table->morphs('createable');
            $table->integer('parent_id')->default(0)->comment("上一级提问/回答");
            $table->integer('top_parent_id')->default(0)->comment("顶层提问/回答");
            $table->integer('video_media_id')->nullable()->comment("视频文件");
            $table->integer('status')->default(0)->comment("0 无状态（有parent_id的都为0） 1 待处理 2 已处理  ");
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
        Schema::dropIfExists('feedback');
    }
}
