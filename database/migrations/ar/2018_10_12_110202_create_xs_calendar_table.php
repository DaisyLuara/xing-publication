<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXsCalendarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ar')->create('xs_calendar', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('workday');
            $table->smallInteger('weekend');
            $table->smallInteger('holiday');
            $table->timestamp('date');
            $table->bigInteger('clientdate');
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
        Schema::connection('ar')->dropIfExists('xs_calendar');
    }
}
