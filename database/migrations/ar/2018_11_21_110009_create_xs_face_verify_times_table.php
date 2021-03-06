<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXsFaceVerifyTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ar')->create('xs_face_verify_times', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('oid');
            $table->string('belong', 20);
            $table->integer('verifytimes');
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
        Schema::connection('ar')->dropIfExists('xs_face_verify_times');
    }
}
