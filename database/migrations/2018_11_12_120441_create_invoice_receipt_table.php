<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceReceiptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_receipts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('receipt_company')->comment('付款公司');
            $table->string('receipt_money')->comment('收款金额');
            $table->string('receipt_date')->comment('到账日期');
            $table->smallInteger('claim_status')->comment('0:未认领,1:已认领');
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
        Schema::dropIfExists('invoice_receipts');
    }
}
