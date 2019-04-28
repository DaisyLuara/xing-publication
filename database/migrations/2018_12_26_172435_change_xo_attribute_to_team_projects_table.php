<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeXoAttributeToTeamProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('team_projects', function (Blueprint $table) {
            $table->smallInteger('xo_attribute')->nullable()->default(0)->comment('小偶属性 1:是,0:否')->change();
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
            $table->smallInteger('xo_attribute')->comment('小偶属性 1:是,0:否')->change();
        });
    }
}
