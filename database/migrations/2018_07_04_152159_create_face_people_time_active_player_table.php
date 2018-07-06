<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacePeopleTimeActivePlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('ar')->hasTable('face_people_time_active_player')) {
            Schema::connection('ar')->create('face_people_time_active_player', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('oid');
                $table->string('belong');
                $table->integer('playernum7');
                $table->integer('playernum20');
                $table->integer('playernum30');
                $table->timestamp('date')->nullable();
                $table->index('oid');
                $table->index('belong');
                $table->index('date');
            });

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('ar')->dropIfExists('face_people_time_active_player');
    }
}
