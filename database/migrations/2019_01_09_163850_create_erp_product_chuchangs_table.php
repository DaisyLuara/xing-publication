<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateErpProductChuchangsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('erp_product_chuchangs', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('contract_id')->nullable()->comment('合同ID');
			$table->text('product_content', 65535)->nullable()->comment('硬件出厂详情');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('erp_product_chuchangs');
	}

}
