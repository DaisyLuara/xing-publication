<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeekRankingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('week_rankings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ar_user_id');
            $table->string('ar_user_name');
            $table->integer('point_id');
            $table->string('point_name');
            $table->integer('scene_id');
            $table->string('scene_name');
            $table->integer('looknum_average');
            $table->integer('ranking');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamp('date')->nullable();
            $table->index('ar_user_id');
            $table->index('point_id');
            $table->index('scene_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('week_rankings');
    }
}
