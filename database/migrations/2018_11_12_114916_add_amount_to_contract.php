<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAmountToContract extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('amount')->after('type');
            $table->string('legal_message')->nullable()->after('remark');
            $table->string('legal_ma_message')->nullable()->after('legal_message');
            $table->string('bd_ma_message')->nullable()->after('legal_ma_message');
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
            $table->dropColumn('amount');
            $table->dropColumn('legal_message');
            $table->dropColumn('legal_ma_message');
            $table->dropColumn('bd_ma_message');
        });
    }
}
