<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSceneAndWriteOffMarketidToCouponBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_batches', function (Blueprint $table) {
            $table->enum('scene_type', [1, 2,3,4])->default(1)->comment('场景类型 - 1:商场通用,2:商场自营,3:商户通用,4:商户自营');
            $table->unsignedInteger('write_off_mid')->nullable()->comment('核销商场');
            $table->text('write_off_sid')->nullable()->comment('核销商户');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupon_batches', function (Blueprint $table) {
            $table->dropColumn('scene_type');
            $table->dropColumn('write_off_mid');
            $table->dropColumn('write_off_sid');
        });
    }
}
