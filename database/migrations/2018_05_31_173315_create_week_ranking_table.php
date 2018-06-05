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
        Schema::create('week_ranking', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ar_user_id');
            $table->integer('point_id');
            $table->integer('looknum_average');
            $table->string('start_date', 20);
            $table->string('end_date', 20);
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
        Schema::dropIfExists('week_ranking');
    }
}
