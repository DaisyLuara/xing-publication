<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateErpLocationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('erp_locations', function(Blueprint $table)
		{
			$table->integer('id', true)->comment('库位ID');
			$table->string('name')->nullable()->comment('库位名称');
			$table->string('warehouse_id')->default('')->comment('对应仓库ID');
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
		Schema::drop('erp_locations');
	}

}
