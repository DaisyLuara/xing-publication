<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToTeamProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('team_projects', function (Blueprint $table) {
            $table->string('project_attribute')->comment('0不计入 1基础条目 2简单条目 5简单条目 6通用节目 7 项目（其中的3 定制节目 4 定制项目已弃用）')->change();
            $table->integer('hidol_attribute')->default(0)->after("project_attribute")->comment("Hidol属性：0 否 1 是");
            $table->integer('individual_attribute')->default(0)->after("hidol_attribute")->comment("定制属性：0 否 1 是");
            $table->integer('contract_id')->nullable()->after("individual_attribute")->comment('定制合同ID');
            $table->string('interaction_attribute')->after("contract_id")->comment("交互技术属性：interaction_api中间件属性,interaction_linkage联动引擎属性(多个以都=逗号隔开)");
            $table->integer('plan_media_id')->nullable()->after("media_id")->comment("[节目交互]文档");
            $table->integer('animation_media_id')->nullable()->after("media_id")->comment("设计动画素材");
            $table->integer('tester_media_id')->nullable()->after("media_id")->comment("测试文档资料");
            $table->integer('operation_media_id')->nullable()->after("media_id")->comment("运营文档资料");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('team_projects', function (Blueprint $table) {
            $table->string('project_attribute')->comment('1基础条目 2简单条目 3 定制节目 4 定制项目')->change();
            $table->dropColumn('hidol_attribute');
            $table->dropColumn('individual_attribute');
            $table->dropColumn('contract_id');
            $table->dropColumn('interaction_attribute');
            $table->dropColumn('plan_media_id');
            $table->dropColumn('animation_media_id');
            $table->dropColumn('tester_media_id');
            $table->dropColumn('operation_media_id');

        });
    }
}
