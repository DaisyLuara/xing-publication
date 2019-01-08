<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function(Blueprint $table)
		{
			$table->integer('id', true)->comment('产品ID');
			$table->string('model')->nullable()->comment('产品型号');
			$table->string('color')->nullable()->comment('产品颜色');
			$table->string('supplier')->nullable()->comment('供应商ID');
			$table->string('remark')->nullable()->comment('备注');
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
		Schema::drop('products');
	}

}
