<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateErpProductAttributesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('erp_product_attributes', function(Blueprint $table)
		{
			$table->integer('id', true)->comment('ID');
			$table->string('product_sku')->nullable()->comment('产品SKU');
			$table->integer('attributes_id')->nullable()->comment('产品属性ID');
			$table->string('attributes_value')->nullable()->comment('产品属性值');
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
		Schema::drop('erp_product_attributes');
	}

}
