<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartDateToCoupon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->tinyInteger('is_fixed_date')->comment('是否固定日期,1:固定,0:不固定')->default(0);
            $table->integer('delay_effective_day')->comment('延后生效天数')->default(0);
            $table->integer('effective_day')->comment('有效天数')->default(0);
            $table->timestamp('start_date')->comment('开始日期')->nullable();
            $table->timestamp('end_date')->comment('结束日期')->nullable();
            $table->timestamp('use_date')->comment('使用日期')->nullable();
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
            $table->dropColumn('is_fixed_date');
            $table->dropColumn('delay_effective_day');
            $table->dropColumn('effective_day');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropColumn('use_date');
        });
    }
}
