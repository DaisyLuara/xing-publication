<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMessageToInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('bd_ma_message')->nullable()->after('remark')->comment('bd主管意见');
            $table->string('legal_ma_message')->nullable()->after('bd_ma_message')->comment('法务主管意见');
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
            $table->dropColumn('bd_ma_message');
            $table->dropColumn('legal_ma_message');
        });
    }
}
