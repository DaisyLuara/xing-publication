<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGetDateToTeamPersonRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('team_person_rewards', function (Blueprint $table) {
            $table->timestamp("get_date")->nullable()->comment("发放日期");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('team_person_rewards', function (Blueprint $table) {
            $table->dropColumn("get_date");
        });
    }
}
