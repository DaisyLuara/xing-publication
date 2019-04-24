<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateErpProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('erp_products', function(Blueprint $table)
		{
			$table->integer('id', true)->comment('产品ID');
			$table->string('sku')->nullable()->comment('产品SKU');
			$table->integer('supplier')->nullable()->comment('供应商ID');
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('erp_products');
	}

}
