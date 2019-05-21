<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDemandApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demand_applications', static function (Blueprint $table) {

            $table->integer('has_contract')->default(0)->comment('是否有合同:0无合同 1有合同 2合同审批中')->change();
            $table->text('small_screen_demand')->nullable()->after('big_screen_demand')->comment('小屏定制内容');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('team_person_rewards', static function (Blueprint $table) {
            $table->boolean('has_contract')->default(false)->comment('是否有合同')->change();
        });
    }
}
