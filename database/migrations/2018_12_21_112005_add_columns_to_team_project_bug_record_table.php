<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToTeamProjectBugRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('team_project_bug_records', function (Blueprint $table) {
            $table->timestamp('occur_date')->nullable()->after("date")->comment("bug事件发生日期");
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
            $table->dropColumn('occur_date');
        });
    }
}
