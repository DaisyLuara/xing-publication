<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContractProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contract_products', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('contract_id')->nullable()->comment('合同 ID');
			$table->string('product_name')->nullable()->comment('硬件型号');
			$table->string('product_color')->nullable()->comment('硬件颜色');
			$table->string('product_stock')->nullable()->comment('硬件数量');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('contract_products');
	}

}
