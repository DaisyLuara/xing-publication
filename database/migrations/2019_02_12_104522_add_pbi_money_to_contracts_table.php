<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPbiMoneyToContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->decimal('pbi_money')->nullable()->default(null)->comment('pbi奖金总数，即：合同收款金额-费用');
            $table->timestamp('pbi_date')->nullable()->default(null)->comment('pbi奖金发放时间');
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
            $table->dropColumn("pbi_money");
            $table->dropColumn("pbi_date");
        });
    }
}
