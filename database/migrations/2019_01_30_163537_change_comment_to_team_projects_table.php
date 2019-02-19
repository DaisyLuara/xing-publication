<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCommentToTeamProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('team_projects', function (Blueprint $table) {
            $table->smallInteger('individual_attribute')
                ->default(0)
                ->comment('定制属性：0 非定制 1 定制特别节目 2 定制普通节目')->change();

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
            $table->smallInteger('individual_attribute')
                ->default(0)
                ->comment('定制属性：0 否 1 是')->change();
        });
    }
}
