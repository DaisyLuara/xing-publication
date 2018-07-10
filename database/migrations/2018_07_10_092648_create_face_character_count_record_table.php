<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaceCharacterCountRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('face_character_count_records', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('date');
        });
        DB::table('face_character_count_records')->insert(['date' => '2017-04-21']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('face_character_count_records');
    }
}
