<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('contract_number')->comment('合同编号');
            $table->string('name');
            $table->integer('company_id');
            $table->integer('applicant')->comment('申请人');
            $table->smallInteger('status')->comment('1:待审批,2:审批中,3:已审批,4:特批,5:驳回');
            $table->integer('handler')->nullable()->comment('处理人');
            $table->smallInteger('type')->comment('0:收款合同,1:付款合同');
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('contracts');
    }
}
