<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contract_id')->comment('合同id');
            $table->integer('applicant')->comment('申请人');
            $table->integer('handler')->nullable()->comment('处理人');
            $table->smallInteger('type')->comment('0:专票,1:普票');
            $table->string('taxpayer_num')->commmet('纳税人识别号');
            $table->string('phone')->comment('手机');
            $table->string('address')->comment('地址');
            $table->string('account_bank')->comment('开户银行');
            $table->string('account_number')->comment('开户账号');
            $table->smallInteger('status')->comment('1:待审批,2:审批中,3:已审批,4:已开票,5:已认领');
            $table->smallInteger('receive_status')->comment('0:未收款,1:已收款');
            $table->string('kind')->comment('种类');
            $table->integer('total')->comment('总计');
            $table->string('total_text')->comment('总计大写');
            $table->string('remark')->nullable()->comment('备注');
            $table->timestamps();
            $table->index('contract_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
