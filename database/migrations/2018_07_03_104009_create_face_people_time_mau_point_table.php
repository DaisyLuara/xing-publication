<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacePeopleTimeMauPointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ar')->create('face_people_time_mau_point', function (Blueprint $table) {
            $table->increments('id');
            $table->string('oid');
            $table->integer('playernum');
            $table->timestamp('date')->nullable();
            $table->index('oid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('ar')->dropIfExists('face_people_time_mau_point');
    }
}
