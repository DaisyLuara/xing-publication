<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocationProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('location_products', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('location_id')->nullable()->comment('库位ID');
			$table->integer('product_id')->nullable()->comment('产品ID');
			$table->integer('stock')->nullable()->comment('库存数量');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('location_products');
	}

}
