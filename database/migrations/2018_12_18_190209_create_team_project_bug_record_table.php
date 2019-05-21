<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamProjectBugRecordTable extends Migration
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
            $table->integer('team_project_id')->comment("项目ID");
            $table->string('project_name')->comment("项目节目名称");
            $table->string('belong')->comment("项目节目标识");
            $table->integer("user_id")->comment("用户ID");
            $table->string("duty")->nullable()->comment("用户职责:tester_quality测试|operation_quality运营");
            $table->integer('bug_num')->comment('bug 数量');
            $table->timestamp('date')->nullable()->comment("bug记录时间 只能是1月1 4月1 7月1 10月1");
            $table->text('description')->comment("bug简单描述")->nullable();
            $table->integer('recorder_id')->comment("记录用户ID");
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
        Schema::dropIfExists('team_project_bug_records');
    }
}
