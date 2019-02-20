<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXsProjectLaunchPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('ar')->hasTable('xs_project_launch_policies')) {
            Schema::connection('ar')->create('xs_project_launch_policies', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('default_plid')->index()->comment('节目ID');
                $table->unsignedInteger('policy_id')->index()->comment('策略ID');
                $table->unsignedInteger('oid')->index()->comment('点位ID');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('ar')->dropIfExists('xs_project_launch_policies');
    }
}
