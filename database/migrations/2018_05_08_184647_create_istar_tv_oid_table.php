<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIstarTvOidTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_launches', function (Blueprint $table) {
            $table->integer('tvoid', true);
            $table->integer('cid')->comment('公司ID');
            $table->integer('pid')->comment('产品ID');
            $table->integer('oid')->comment('门店ID,0为通用');
            $table->integer('default_plid')->comment('默认子产品ID');
            $table->integer('weekday_tvid')->comment('工作日默认模板ID');
            $table->integer('weekend_tvid')->comment('周末默认模板ID');
            $table->bigInteger('sdate')->default(0)->comment('开始日期');
            $table->bigInteger('edate')->default(0)->comment('结束日期');
            $table->integer('div_tvid')->comment('自定义默认模板ID');
            $table->string('date', 20);
            $table->bigInteger('clientdate')->default(0)->comment('时间');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('project_launches');
    }

}
