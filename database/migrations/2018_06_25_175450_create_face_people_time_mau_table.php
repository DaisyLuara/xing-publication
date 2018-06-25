<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacePeopleTimeMauTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ar')->create('face_people_time_mau', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('oid');
            $table->string('belong');
            $table->integer('playernum');
            $table->timestamp('date')->nullable();
            $table->index(['id', 'oid', 'belong', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('ar')->dropIfExists('face_people_time_mau');
    }
}
