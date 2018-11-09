<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('payee');
            $table->dropColumn('account_bank');
            $table->dropColumn('account_number');
            $table->integer('payment_payee_id')->after('reason')->comment('收款人id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payee');
            $table->string('account_bank');
            $table->string('account_number');
            $table->dropColumn('payment_payee_id');
        });
    }
}
