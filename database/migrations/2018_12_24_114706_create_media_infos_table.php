<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('media_id')->comment("媒体ID");
            $table->string('name')->nullable()->comment("文档名称");
            $table->string('type')->comment("文档类型，节目运营文档 project_operation ");
            $table->timestamp('date')->nullable()->comment("上传日期");
            $table->integer("recorder_id")->comment("上传者");
            $table->softDeletes();
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
        Schema::dropIfExists('media_infos');
    }
}
