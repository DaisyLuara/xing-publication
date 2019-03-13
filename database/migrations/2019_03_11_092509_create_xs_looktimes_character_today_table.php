<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXsLooktimesCharacterTodayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xs_looktimes_character_today', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('oid');
            $table->string('belong', 20);
            $table->string('time', 20);
            $table->integer('century10_bnum');
            $table->integer('century10_gnum');
            $table->integer('century00_bnum');
            $table->integer('century00_gnum');
            $table->integer('century90_bnum');
            $table->integer('century90_gnum');
            $table->integer('century80_bnum');
            $table->integer('century80_gnum');
            $table->integer('century70_bnum');
            $table->integer('century70_gnum');
            $table->timestamp('date');
            $table->bigInteger('clientdate');
            $table->index('oid');
            $table->index('belong');
            $table->index('clientdate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xs_looktimes_character_today');
    }
}
