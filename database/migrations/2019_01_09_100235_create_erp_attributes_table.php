<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateErpAttributesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('erp_attributes', function(Blueprint $table)
		{
			$table->integer('id', true)->comment('属性ID');
			$table->string('name')->nullable()->comment('产品属性');
			$table->string('display_name')->nullable();
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
		Schema::drop('erp_attributes');
	}

}
