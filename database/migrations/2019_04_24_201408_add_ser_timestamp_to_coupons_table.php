<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSerTimestampToCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->integer('ser_timestamp')->nullable()->after('qiniu_id')->comment('微信消息时间');
            $table->index(['qiniu_id', 'belong', 'ser_timestamp']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('ser_timestamp');
            $table->dropIndex('coupons_qiniu_id_belong_ser_timestamp_index');
        });
    }
}
