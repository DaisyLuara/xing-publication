<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractCostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_costs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contract_id');
            $table->integer('applicant_id')->comment('所属人id');
            $table->string('applicant_name')->comment('所属人');
            $table->string('confirm_cost')->default(0)->comment('已确认成本');
            $table->string('total_cost')->comment('总成本');
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
        Schema::dropIfExists('contract_costs');
    }
}
