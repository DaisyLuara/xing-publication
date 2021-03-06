<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXsFacePhoneTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ar')->create('xs_face_phone_times', function (Blueprint $table) {
            $table->increments('id');
            $table->string('oid', 20);
            $table->string('belong', 20);
            $table->integer('phonetimes');
            $table->integer('oatimes');
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
        Schema::dropIfExists('xs_face_phone_times');
    }
}
