<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPerPersonTimesAndPerPersonPerDayTimesToPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('policies', function (Blueprint $table) {
            $table->integer('type')->default(1)->after('bd_user_id')->comment('策略类型:1:抽奖,2:券包');
            $table->boolean('per_person_unlimit')->default(true)->after('type')->comment('每人开启无限领取,0:关闭,1:开启,');
            $table->integer('per_person_times')->default(0)->after('per_person_unlimit')->comment('每人领取数量');
            $table->boolean('per_person_per_day_unlimit')->default(true)->after('per_person_times')->comment('每人每天开启无限领取,0:关闭,1:开启');
            $table->integer('per_person_per_day_times')->default(0)->after('per_person_per_day_unlimit')->comment('每人每天领取数量');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('policies', function (Blueprint $table) {
            $table->dropColumn('per_person_unlimit');
            $table->dropColumn('per_person_times');
            $table->dropColumn('per_person_per_day_unlimit');
            $table->dropColumn('per_person_per_day_times');
        });
    }
}
