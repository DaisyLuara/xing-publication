<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Controllers\Admin\Team\V1\Models\TeamBonusRecord;

class CreateTeamBonusRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_bonus_records', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('date')->nullable();
            $table->timestamps();
        });
        TeamBonusRecord::create(['date' => '2018-11-21']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_bonus_records');
    }
}
