<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamPersonRewardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_person_rewards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('project_name');
            $table->string('belong');
            $table->string('experience_money')->nullable()->comment('体验绩效');
            $table->string('xo_money')->nullable()->comment('小偶绩效');
            $table->string('link_money')->nullable()->comment('联动绩效');
            $table->string('system_money')->nullable()->comment('平台绩效');
            $table->string('total')->nullable();
            $table->timestamp('date')->nullable();
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
        Schema::dropIfExists('team_person_rewards');
    }
}
