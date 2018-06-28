<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacePeopleTimeCountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('ar')->hasTable('face_people_time_count')) {
            Schema::connection('ar')->create('face_people_time_count', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('oid');
                $table->string('belong', 20);
                $table->integer('playernum');
                $table->integer('playtime');
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
        Schema::connection('ar')->dropIfExists('face_people_time_count');
    }
}
