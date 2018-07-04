<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacePeopleTimeMauMarketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ar')->create('face_people_time_mau_market', function (Blueprint $table) {
            $table->increments('id');
            $table->string('marketid');
            $table->integer('active_player');
            $table->timestamp('date')->nullable();
            $table->index('marketid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('ar')->dropIfExists('face_people_time_mau_market');
    }
}
