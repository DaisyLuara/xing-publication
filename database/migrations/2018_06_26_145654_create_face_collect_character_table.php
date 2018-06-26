<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaceCollectCharacterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ar')->create('face_collect_character', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('oid');
            $table->string('belong');
            $table->string('time');
            $table->string('century')->comment('00:00后,90:90后,80:80后,70:70后,0:其他年龄段');
            $table->string('gender');
            $table->integer('looknum');
            $table->timestamp('date');
            $table->index(['oid', 'belong', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('ar')->dropIfExists('face_collect_character');
    }
}
