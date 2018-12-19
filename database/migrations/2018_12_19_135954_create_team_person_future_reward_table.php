<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamPersonFutureRewardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_person_future_rewards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('project_name');
            $table->string('belong');
            $table->string('experience_money')->nullable()->comment('体验绩效');
            $table->string('xo_money')->nullable()->comment('小偶绩效');
            $table->string('link_money')->nullable()->comment('联动绩效');
            $table->string('system_money')->nullable()->comment('平台绩效');
            $table->string('total')->nullable();
            $table->timestamp('date')->nullable()->comment("统计绩效的日期");
            $table->index('belong');
            $table->timestamp("get_date")->nullable()->comment("延迟三个月后的发放日期");
            $table->integer('status')->default(0)->comment("0 未发放 1 已发放 -1 因bug取消发放");
            $table->integer("team_project_id")->comment("节目项目ID");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_person_future_rewards');
    }
}
