<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXsFaceCharacterCountTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ar')->create('xs_face_character_count_times', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('oid');
            $table->string('belong', 20);
            $table->string('time', 20);
            $table->integer('century00_bnum');
            $table->integer('century00_gnum');
            $table->integer('century90_bnum');
            $table->integer('century90_gnum');
            $table->integer('century80_bnum');
            $table->integer('century80_gnum');
            $table->integer('century70_bnum');
            $table->integer('century70_gnum');
            $table->integer('century10_bnum');
            $table->integer('century10_gnum');
            $table->timestamp('date')->nullable();
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
        Schema::connection('ar')->dropIfExists('xs_face_character_count_times');
    }
}
