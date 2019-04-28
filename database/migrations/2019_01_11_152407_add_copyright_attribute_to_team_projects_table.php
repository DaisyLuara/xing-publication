<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCopyrightAttributeToTeamProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('team_projects', function (Blueprint $table) {
            $table->integer('copyright_attribute')->after("id")->default(0)->comment("是否为原创 0:原创节目 1:非原创节目");
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
        });
    }
}
