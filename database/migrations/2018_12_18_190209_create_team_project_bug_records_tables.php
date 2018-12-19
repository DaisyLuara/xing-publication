<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamProjectBugRecordsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_project_bug_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('team_project_id')->comment("团队节目ID");
            $table->string('project_name')->comment("节目名称");
            $table->string('belong')->comment("节目标识");
            $table->integer('bug_num')->comment('bug 数量');
            $table->date('date')->comment("出现bug的大致日期");
            $table->text('description')->comment("bug简单描述")->nullable();
            $table->integer('recorder_id')->comment("记录用户ID");
            $table->integer('status')->comment("0 可修改，暂未用于绩效生效 1 不可修改，已用")->default(0);
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
        Schema::table('team_project_bug_records', function (Blueprint $table) {
            Schema::dropIfExists('team_project_bug_records');
        });
    }
}
