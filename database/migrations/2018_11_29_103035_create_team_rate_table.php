<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Controllers\Admin\Team\V1\Models\TeamRate;

class CreateTeamRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('interaction')->comment('interaction:交互技术');
            $table->string('originality')->comment('节目创意');
            $table->string('h5_1')->comment('H5基础');
            $table->string('h5_2')->comment('H5复杂');
            $table->string('animation')->comment('设计动画');
            $table->string('plan')->comment('节目统筹');
            $table->string('tester')->comment('节目测试');
            $table->string('operation')->comment('平台运营');
            $table->timestamps();
        });
        TeamRate::create([
            'interaction' => 0.3,
            'originality' => 0.1,
            'h5_1' => 0.025,
            'h5_2' => 0.1,
            'animation' => 0.16,
            'plan' => 0.06,
            'tester' => 0.12,
            'operation' => 0.04
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_rates');
    }
}
