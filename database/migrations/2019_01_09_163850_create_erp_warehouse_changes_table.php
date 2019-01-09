<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateErpWarehouseChangesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('erp_warehouse_changes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('sku')->nullable()->comment('产品SKU');
			$table->smallInteger('out_location')->nullable()->comment('调出库位');
			$table->smallInteger('in_location')->nullable()->comment('调入库位');
			$table->integer('num')->nullable()->comment('调拨数量');
			$table->string('remark', 11)->nullable()->comment('调拨记录备注');
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
		Schema::drop('erp_warehouse_changes');
	}

}
