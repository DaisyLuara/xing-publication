<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateContractTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('product_status');
            $table->smallInteger('kind')->after('type')->comment('合同种类 1:铺屏,2:销售,3:租赁,4:服务');
            $table->smallInteger('serve_target')->nullable()->after('kind')->comment('服务对象 1:商户,2:商场');
            $table->smallInteger('recharge')->nullable()->after('serve_target')->comment('预充值 0:否,1:是');
            $table->integer('special_num')->after('recharge')->comment('定制节目数量');
            $table->integer('common_num')->after('special_num')->comment('通用节目数量');
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
            $table->smallInteger('product_status')->after('type');
            $table->dropColumn('kind');
            $table->dropColumn('serve_target');
            $table->dropColumn('recharge');
            $table->dropColumn('special_num');
            $table->dropColumn('common_num');
        });
    }
}
