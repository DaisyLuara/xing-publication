<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTeamPersonRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('team_person_rewards', function (Blueprint $table) {
            $table->string('main_type')->default('CPE')->comment('绩效类别')->after('type');
            $table->dropColumn('experience_money');
            $table->dropColumn('xo_money');
            $table->dropColumn('link_money');
            $table->dropColumn('system_money');
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
            $table->dropColumn('main_type');
            $table->string('experience_money')->nullable()->comment('体验绩效');
            $table->string('xo_money')->nullable()->comment('小偶绩效');
            $table->string('link_money')->nullable()->comment('联动绩效');
            $table->string('system_money')->nullable()->comment('平台绩效');
        });
    }
}
