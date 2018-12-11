<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreditAndBsImageUrlToCouponBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_batches', function (Blueprint $table) {
            $table->string('bs_image_url')->nullable()->default('')->after('image_url')->comment('大屏图片链接');
            $table->unsignedInteger('credit')->nullable()->after('campaign_id')->comment('兑换积分');
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
            $table->dropColumn('bs_image_url');
            $table->dropColumn('credit');
        });
    }
}
