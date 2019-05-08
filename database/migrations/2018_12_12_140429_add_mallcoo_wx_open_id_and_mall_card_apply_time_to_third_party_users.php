<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMallcooWxOpenIdAndMallCardApplyTimeToThirdPartyUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('third_party_users', function (Blueprint $table) {
            $table->string('mallcoo_wx_open_id')->nullable()->after('openid')->comment('猫酷 WxOpenId');
            $table->timestamp('mall_card_apply_time')->nullable()->after('birthday')->comment('猫酷 会员开卡时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('third_party_users', function (Blueprint $table) {
            $table->dropColumn('mallcoo_wx_open_id');
            $table->dropColumn('mall_card_apply_time');
        });
    }
}
