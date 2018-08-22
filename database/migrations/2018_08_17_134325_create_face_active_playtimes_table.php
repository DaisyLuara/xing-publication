<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaceActivePlaytimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('face_active_playtimes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('oid');
            $table->string('belong', 20);
            $table->integer('fpid');
            $table->integer('playtimes7');
            $table->integer('playtimes15');
            $table->integer('playtimes21');
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
        Schema::dropIfExists('face_active_playtimes');
    }
}
