<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamBonusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_bonuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('project_name');
            $table->string('belong');
            $table->string('money')->comment('奖金');
            $table->timestamp('date')->nullable();
            $table->string('factor')->comment('系数');
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
        Schema::dropIfExists('team_bonuses');
    }
}
