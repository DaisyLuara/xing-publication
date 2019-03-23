<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePolicyLaunchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policy_launches', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->index()->comment('公司ID');
            $table->unsignedInteger('policy_id')->comment('策略ID');
            $table->unsignedInteger('project_id')->comment('节目ID');
            $table->unsignedInteger('oid')->comment('点位ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('policy_launches');
    }
}
