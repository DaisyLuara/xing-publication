<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdLaunchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ad_launches', function(Blueprint $table)
		{
			$table->integer('aoid', true);
			$table->integer('atid')->comment('行业ID');
			$table->integer('atiid')->comment('广告主ID');
			$table->integer('aid')->index('aid')->comment('广告ID');
			$table->integer('areaid')->comment('区域ID,0为通用');
			$table->integer('marketid')->comment('商场ID,0为通用');
			$table->integer('oid')->index('oid')->comment('门店ID,0为通用');
			$table->integer('ktime')->comment('秒为单位');
			$table->bigInteger('sdate')->default(0)->comment('开始日期');
			$table->bigInteger('edate')->default(0)->comment('结束日期');
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
		Schema::drop('ad_launches');
	}

}
