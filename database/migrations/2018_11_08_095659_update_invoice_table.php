<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('taxpayer_num');
            $table->dropColumn('invoice_company');
            $table->dropColumn('phone');
            $table->dropColumn('telephone');
            $table->dropColumn('address');
            $table->dropColumn('account_bank');
            $table->dropColumn('account_number');
            $table->integer('invoice_company_id')->after('type')->comment('开票公司id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('taxpayer_num');
            $table->string('invoice_company');
            $table->string('phone')->nullable();
            $table->string('telephone')->nullable();
            $table->string('address');
            $table->string('account_bank');
            $table->string('account_number');
            $table->dropColumn('invoice_company_id');
        });
    }
}
