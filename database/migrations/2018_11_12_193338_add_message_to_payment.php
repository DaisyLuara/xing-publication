<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMessageToPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('bd_ma_massage')->nullable()->after('remark')->comment('bd主管意见');
            $table->string('legal_message')->nullable()->after('bd_ma_massage')->comment('法务意见');
            $table->string('legal_ma_message')->nullable()->after('legal_message')->comment('法务主管意见');
            $table->string('auditor_message')->nullable()->after('legal_ma_message')->comment('审计意见');
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
            $table->dropColumn('bd_ma_massage');
            $table->dropColumn('legal_message');
            $table->dropColumn('legal_ma_message');
            $table->dropColumn('auditor_message');
        });
    }
}
