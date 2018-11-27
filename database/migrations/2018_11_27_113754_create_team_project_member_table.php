<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamProjectMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_project_members', function (Blueprint $table) {
            $table->integer('team_project_id');
            $table->integer('user_id');
            $table->string('type')->comment('interaction:交互技术,originality:节目创意,h5:H5开发,animation:设计动画,plan:节目统筹,tester:节目测试,operation:平台运营');
            $table->string('rate')->comment('比例');
            $table->index('team_project_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_project_members');
    }
}
