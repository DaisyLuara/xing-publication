<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateErpWarehousesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('erp_warehouses', function(Blueprint $table)
		{
			$table->integer('id', true)->comment('仓库ID');
			$table->string('name')->nullable()->comment('仓库名称');
			$table->string('address')->default('')->comment('仓库地址');
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
		Schema::drop('erp_warehouses');
	}

}
