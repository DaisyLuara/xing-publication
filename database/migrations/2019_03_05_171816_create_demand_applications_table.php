<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemandApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demand_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->string("title")->unique()->comment("项目标的(唯一)");
            $table->integer("applicant_id")->comment("申请人ID");
            $table->text("launch_point_remark")->nullable()->comment("投放地点备注");

            $table->boolean("has_contract")->default(false)->comment("是否有合同");
            //合同 一对多的表

            $table->integer("project_num")->comment("节目数量");
            $table->text("similar_project_name")->nullable()->comment("类似节目");

            $table->timestamp("expect_online_time")->comment("期望上线时间");
            $table->string("expect_receiver_ids")->comment("期望接单人ID(逗号隔开)");

            $table->text("big_screen_demand")->nullable()->comment("大屏节目需求");
            $table->text("h5_demand")->nullable()->comment("H5节目需求");
            $table->text("other_demand")->nullable()->comment("其他定制内容");
            $table->text("applicant_remark")->nullable()->comment("申请人备注");

            $table->integer("status")->default(0)->comment("0:未接单 1:已完成 2:已接单 3:修改中");

            $table->integer("receiver_id")->nullable()->comment("接单人ID");
            $table->string("receiver_name")->nullable()->comment("接单人名称");
            $table->text("receiver_remark")->nullable()->comment("接单人备注");
            $table->timestamp("receiver_time")->nullable()->comment("接单人备注");

            $table->integer("confirm_id")->nullable()->comment("确认完成人");
            $table->string("confirm_name")->nullable()->comment("确认完成人名称");
            $table->timestamp("confirm_time")->nullable()->comment("确认完成时间");

            $table->timestamps();//申请时间 created_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demand_applications');
    }
}
