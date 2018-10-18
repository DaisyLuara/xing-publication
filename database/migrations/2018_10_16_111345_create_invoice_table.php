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
            $table->integer('contract_id');
            $table->string('applicant');
            $table->integer('handler')->nullable();
            $table->smallInteger('type')->comment('0:专票,1:普票');
            $table->string('taxpayer_num')->commmet('纳税人识别号');
            $table->string('phone');
            $table->string('address');
            $table->string('account_bank');
            $table->string('account_number');
            $table->smallInteger('status')->comment('1:待审批,2:审批中,3:已开票,4:已认领');
            $table->smallInteger('receive_status')->comment('0:未收款,1:已收款');
            $table->string('kind');
            $table->integer('total');
            $table->string('remark');
            $table->integer('create_user_id');
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
