<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamSystemProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_system_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('平台项目名称');
            $table->integer('applicant')->comment('申请人');
            $table->string('status')->comment('1:申请中,2:已分配,3:已驳回');
            $table->string('remark')->nullable()->comment('备注');
            $table->string('reject_message')->nullable()->comment('驳回意见');
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
        Schema::dropIfExists('team_system_projects');
    }
}
