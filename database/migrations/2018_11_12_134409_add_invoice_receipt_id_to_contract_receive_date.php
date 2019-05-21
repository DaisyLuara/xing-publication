<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceReceiptIdToContractReceiveDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_receive_dates', function (Blueprint $table) {
            $table->integer('invoice_receipt_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_receive_dates', function (Blueprint $table) {
            $table->dropColumn('invoice_receipt_id');
        });
    }
}
