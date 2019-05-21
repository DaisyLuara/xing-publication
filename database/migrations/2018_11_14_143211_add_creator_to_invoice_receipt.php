<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreatorToInvoiceReceipt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_receipts', function (Blueprint $table) {
            $table->string('creator')->nullable()->after('claim_status')->comment('创建人');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_receipts', function (Blueprint $table) {
            $table->dropColumn('creator');
        });
    }
}
