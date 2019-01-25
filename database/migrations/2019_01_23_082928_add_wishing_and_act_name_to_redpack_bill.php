<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWishingAndActNameToRedpackBill extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('redpack_bill', function (Blueprint $table) {
            $table->string('wishing', 128)->default('')->comment('红包祝福语');
            $table->string('act_name', 256)->default('')->comment('活动名称');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('redpack_bill', function (Blueprint $table) {
            $table->dropColumn('wishing');
            $table->dropColumn('act_name');
        });
    }
}
