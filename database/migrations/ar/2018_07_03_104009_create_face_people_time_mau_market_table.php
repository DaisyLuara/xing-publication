<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacePeopleTimeMauMarketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::connection('ar')->hasTable('xs_face_mau_market')) {
            Schema::connection('ar')->create('xs_face_mau_market', function (Blueprint $table) {
                $table->increments('id');
                $table->string('marketid');
                $table->integer('active_player');
                $table->timestamp('date')->nullable();
                $table->index('marketid');
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
        Schema::connection('ar')->dropIfExists('xs_face_mau_market');
    }
}
