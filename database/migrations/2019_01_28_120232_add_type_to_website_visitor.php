<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToWebsiteVisitor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('website_visitors', function (Blueprint $table) {
            $table->smallInteger('type')->default(0)->after('subscribe')->comment('合作对象类型:1:商业综合体&商户 2:品牌客户 3:加盟代理');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('website_visitors', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
