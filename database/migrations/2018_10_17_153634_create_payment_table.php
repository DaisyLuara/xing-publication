<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contract_id')->comment('合同id');
            $table->integer('applicant')->comment('申请人');
            $table->string('amount')->comment('申请金额');
            $table->smallInteger('status')->comment('1:待审批,2:审批中,3:已审批,4:已付款,5:驳回');
            $table->integer('handler')->nullable()->comment('处理人');
            $table->smallInteger('receive_status')->comment('0:未收票,1:已收票');
            $table->smallInteger('type')->comment('1:支票,2:电汇单,3:贷记凭证');
            $table->string("reason")->comment('申请事由');
            $table->string('payee')->comment('收款人');
            $table->string("account_bank")->comment('开户行');
            $table->string('account_number')->comment('开户账号');
            $table->string('remark')->nullable()->comment('备注');
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
        Schema::dropIfExists('payments');
    }
}
