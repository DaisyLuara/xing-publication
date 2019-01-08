<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHardwareChuchangsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hardware_chuchangs', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('contract_id')->nullable()->comment('合同ID');
			$table->text('hardware_content', 65535)->nullable()->comment('硬件出厂详情');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hardware_chuchangs');
	}

}
