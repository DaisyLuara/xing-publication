<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRejectMessageToContract extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('legal_reject_message')->nullable()->after('bd_ma_message')->comment('法务驳回意见');
            $table->string('legal_ma_reject_message')->nullable()->after('legal_reject_message')->comment('法务主管驳回意见');
            $table->string('bd_ma_reject_message')->nullable()->after('legal_ma_reject_message')->comment('bd主管意见');
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
            $table->dropColumn('legal_reject_message');
            $table->dropColumn('legal_ma_reject_message');
            $table->dropColumn('bd_ma_reject_message');
        });
    }
}
