<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFaceBindToThirdPartyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('third_party_users', function (Blueprint $table) {
            $table->tinyInteger('face_bind')->after('mall_card_apply_time')->default(0)->comment('是否绑定人脸 0:未绑定 1:已绑定');
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
            $table->dropColumn('face_bind');
        });
    }
}
