<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCopyrightProjectToTeamProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('team_projects', function (Blueprint $table) {
            $table->string('project_attribute')->comment('0不计入 1基础条目 2简单条目 3通用节目 4项目')->change();
            $table->integer('copyright_attribute')->after("id")->default(0)->comment("是否为原创 0:原创节目 1:非原创节目");
            $table->integer('copyright_project_id')->nullable()->after("copyright_attribute")->comment("原创节目");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('team_projects', function (Blueprint $table) {
            $table->dropColumn("copyright_attribute");
            $table->dropColumn("copyright_project_id");
        });
    }
}
