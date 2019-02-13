<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractCostContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_cost_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cost_id')->comment('成本id');
            $table->integer('creator_id')->comment('创建人id');
            $table->string('creator')->comment('创建人');
            $table->integer('kind_id')->comment('成本类型');
            $table->string('money')->comment('成本金额');
            $table->string('remark')->nullable()->comment('备注');
            $table->smallInteger('status')->comment('0:未确认,1:已确认');
            $table->string('operator')->comment('最后操作者');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_cost_contents');
    }
}
