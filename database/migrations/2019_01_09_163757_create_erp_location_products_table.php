<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateErpLocationProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('erp_location_products', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('location_id')->nullable()->comment('库位ID');
			$table->string('product_sku')->nullable()->comment('产品sku');
            $table->integer('stock')->default(0)->comment('库存数量');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('erp_location_products');
	}

}
