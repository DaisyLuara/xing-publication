<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWxUserIdToThirdPartyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('third_party_users', function (Blueprint $table) {
            $table->integer('wx_user_id')->index()->default(0)->after('mobile');
            $table->string('z')->default('')->change();
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
            $table->dropColumn('wx_user_id');
        });
    }
}
