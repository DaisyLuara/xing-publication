<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacePeopleTimeMauTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('ar')->hasTable('xs_mau')) {
            Schema::connection('ar')->create('xs_mau', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('active_player');
                $table->timestamp('date')->nullable();
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
        Schema::connection('ar')->dropIfExists('xs_mau');
    }
}
