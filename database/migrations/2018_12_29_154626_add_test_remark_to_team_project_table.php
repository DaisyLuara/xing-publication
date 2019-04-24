<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTestRemarkToTeamProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('team_projects', function (Blueprint $table) {
            $table->text('test_remark')->nullable()->comment('测试备注');
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
            $table->dropColumn('test_remark');
        });
    }
}
