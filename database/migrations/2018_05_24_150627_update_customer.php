<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('avatar')->nullable();
            $table->string('introduction')->nullable();
            $table->integer('notification_count')->unsigned()->default(0);
            $table->string('weixin_openid')->unique()->nullable();
            $table->string('weixin_unionid')->unique()->nullable();
            $table->integer('ar_user_id')->index()->nullable()->comment('星视度用户ID');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('avatar');
            $table->dropColumn('introduction');
            $table->dropColumn('notification_count');
            $table->dropColumn('weixin_openid');
            $table->dropColumn('weixin_unionid');
            $table->dropColumn('ar_user_id');
            $table->dropSoftDeletes();
        });
    }
}
