<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemandModifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demand_modifies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("demand_application_id")->comment("需求申请ID");
            $table->integer("applicant_id")->comment("申请人ID");
            $table->string("title")->comment("需求修改标题");
            $table->text("content")->comment("需求修改详情");

            $table->boolean("has_feedback")->default(false)->comment("是否反馈");
            $table->text("feedback")->nullable()->comment("反馈内容");
            $table->timestamp("feedback_time")->nullable()->comment("反馈时间");
            $table->integer("feedback_person_id")->nullable()->comment("反馈人ID");
            $table->string("feedback_person_name")->nullable()->comment("反馈人ID");


            $table->integer("status")->default(0)->comment("0:待处理 1:已审核 2：已驳回 ");


            $table->integer("reviewer_id")->nullable()->comment("审核人ID");
            $table->string("reviewer_name")->nullable()->comment("审核人名称");
            $table->integer("review_time")->nullable()->comment("审核时间");
            $table->text("reject_remark")->nullable()->comment("驳回备注");

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
        Schema::dropIfExists('demand_modifies');
    }
}
