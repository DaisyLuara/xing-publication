<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRejectMessageToInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('bd_ma_reject_message')->nullable()->after('legal_ma_message')->comment('bd主管驳回意见');
            $table->string('legal_ma_reject_message')->nullable()->after('legal_reject_message')->comment('法务主管驳回意见');
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
            $table->dropColumn('bd_ma_reject_message');
            $table->dropColumn('legal_ma_reject_message');
        });
    }
}
