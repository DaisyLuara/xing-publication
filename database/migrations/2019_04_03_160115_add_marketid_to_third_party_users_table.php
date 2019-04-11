<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMarketidToThirdPartyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('third_party_users', function (Blueprint $table) {
            $table->unsignedInteger('marketid')->after('mobile')->default(0)->comment('商场ID');
            $table->string('z')->index()->after('mobile')->comment('ar用户标识');
            $table->dropUnique('third_party_users_mobile_unique');
            $table->unique(['mobile', 'marketid']);
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
            $table->dropColumn('marketid');
            $table->dropColumn('z');
            $table->dropUnique('third_party_users_mobile_marketid_unique');
            $table->unique('mobile');
        });
    }
}
