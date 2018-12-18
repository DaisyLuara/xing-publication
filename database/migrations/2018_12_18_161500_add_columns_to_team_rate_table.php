<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToTeamRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('team_rates', function (Blueprint $table) {
            $table->renameColumn('interaction', 'interaction_api')->comment('交互技术-中间件调用')->change();
            $table->string('backend_docking')->comment('后端IT技术对接');
            $table->string('interaction_linkage')->comment('交互技术-联动引擎');
            $table->string('tester_quality')->comment('节目测试-保质');
            $table->string('operation_quality')->comment('节目运营-保质');
            $table->string('animation_hidol')->comment('设计动画-hidol对接');
            $table->string('hidol_patent')->comment('hidol专利');
        });
        App\Http\Controllers\Admin\Team\V1\Models\TeamRate::where('id', 1)
            ->update([
                'originality' => 0.1,        // 创意
                'plan' => 0.06,              // 统筹
                'animation' => 0.25,         // 设计&动画
                'animation_hidol' => 0.05,   // 设计&动画-Hidol对接
                'hidol_patent' => 0.04,      // Hidol专利
                'interaction_api' => 0.1,    // 交互技术-中间件
                'interaction_linkage' => 0.1,// 交互技术-联动引擎
                'backend_docking' => 0.05,   // 后端IT技术对接
                'h5_1' => 0.025,             // H5开发简单
                'h5_2' => 0.1,               // H5开发复杂
                'tester' => 0.03,            // 测试基本
                'tester_quality' => 0.06,    // 测试保质
                'operation' => 0.02,         // 运营基本
                'operation_quality' => 0.04, // 运营保质
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('team_rates', function (Blueprint $table) {
            $table->renameColumn('interaction_api', 'interaction');
            $table->dropColumn('backend_docking');
            $table->dropColumn('interaction_linkage');
            $table->dropColumn('tester_quality');
            $table->dropColumn('operation_quality');
            $table->dropColumn('animation_hidol');
            $table->dropColumn('hidol_patent');
        });
    }
}
