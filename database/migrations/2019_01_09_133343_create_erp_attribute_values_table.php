<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateErpAttributeValuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('erp_attribute_values', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('attribute_id')->comment('属性ID');
			$table->string('value')->nullable()->comment('属性名称');
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
		Schema::drop('erp_attribute_values');
	}

}
