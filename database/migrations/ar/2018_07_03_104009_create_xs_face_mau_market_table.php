<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXsFaceMauMarketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::connection('ar')->create('xs_face_mau_market', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('marketid');
            $table->integer('active_player');
            $table->timestamp('date')->nullable();
            $table->index('marketid');
        });
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
