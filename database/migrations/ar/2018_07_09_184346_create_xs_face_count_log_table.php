<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXsFaceCountLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ar')->create('xs_face_count_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('oid');
            $table->string('belong');
            $table->integer('looknum');
            $table->integer('playernum7');
            $table->integer('playernum20');
            $table->integer('playernum30');
            $table->integer('playernum');
            $table->integer('outnum');
            $table->integer('scannum');
            $table->integer('lovenum');
            $table->timestamp('date')->nullable();
            $table->index('oid');
            $table->index('belong');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('ar')->dropIfExists('xs_face_count_log');
    }
}
