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
        if (!Schema::connection('ar')->hasTable('xs_face_collect_character')) {
            Schema::connection('ar')->create('xs_face_collect_character', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('oid');
                $table->string('belong', 20);
                $table->string('time', 20);
                $table->string('century', 20)->comment('00:00后,90:90后,80:80后,70:70后,0:其他年龄段');
                $table->string('gender', 20);
                $table->integer('looknum');
                $table->timestamp('date');
                $table->bigInteger('clientdate');
                $table->index('oid');
                $table->index('belong');
                $table->index('clientdate');
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
        Schema::connection('ar')->dropIfExists('xs_face_collect_character');
    }
}
