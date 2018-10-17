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
            $table->integer('contract_id');
            $table->string('applicant');
            $table->string('amount');
            $table->smallInteger('status')->comment('1:待审批,2:审批中,3:已审批,4:已付款');
            $table->string('processing_person')->nullable()->comment('处理人');
            $table->smallInteger('receive_status')->comment('0:已收票,1:未收票');
            $table->smallInteger('type')->comment('1:支票,2:电汇单,3:贷记凭证');
            $table->string("reason");
            $table->string('payee');
            $table->string("account_bank");
            $table->string('account_number');
            $table->string('remark');
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
