<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('project_name')->comment('节目名称');
            $table->string('belong')->nullable()->comment('节目');
            $table->integer('applicant')->comment('申请人');
            $table->smallInteger('project_attribute')->comment('1:基础条目,2:通用节目,3:定制节目,4:定制项目');
            $table->smallInteger('link_attribute')->comment('联动属性 1:是,0:否');
            $table->smallInteger('h5_attribute')->comment('1:基础模版,2:复杂模版');
            $table->smallInteger('xo_attribute')->comment('小偶属性 1:是,0:否');
            $table->string('begin_date')->nullable()->comment('开始时间');
            $table->string('online_date')->nullable()->comment('上线时间');
            $table->string('launch_date')->nullable()->comment('投放时间');
            $table->string('remark')->nullable()->comment('项目说明');
            $table->smallInteger('status')->comment('1:进行中,2:测试确认,3:运营确认,4:主管确认');
            $table->timestamps();
            $table->index('belong');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_projects');
    }
}
