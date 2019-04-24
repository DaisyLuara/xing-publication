<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeRemarkLengthForProcess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('remark', 1000)->change();
            $table->string('legal_message', 1000)->change();
            $table->string('legal_ma_message', 1000)->change();
            $table->string('bd_ma_message', 1000)->change();
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('remark', 1000)->change();
            $table->string('bd_ma_message', 1000)->change();
            $table->string('legal_ma_message', 1000)->change();
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->string('remark', 1000)->change();
            $table->string('bd_ma_message', 1000)->change();
            $table->string('legal_message', 1000)->change();
            $table->string('legal_ma_message', 1000)->change();
            $table->string('auditor_message', 1000)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('remark')->change();
            $table->string('legal_message')->change();
            $table->string('legal_ma_message')->change();
            $table->string('bd_ma_message')->change();
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('remark')->change();
            $table->string('bd_ma_message')->change();
            $table->string('legal_ma_message')->change();
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->string('remark')->change();
            $table->string('bd_ma_message')->change();
            $table->string('legal_message')->change();
            $table->string('legal_ma_message')->change();
            $table->string('auditor_message')->change();
        });
    }
}
