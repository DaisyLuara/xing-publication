<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateContractReceiveDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_receive_dates', function (Blueprint $table) {
            $table->renameColumn('date', 'receive_date');
            $table->smallInteger('receive_status')->comment('0：未收款，1：已收款');
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
            $table->renameColumn('receive_date', 'date');
            $table->dropColumn('receive_status');
        });
    }
}
