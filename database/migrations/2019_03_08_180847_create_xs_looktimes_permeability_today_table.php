<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXsLooktimesPermeabilityTodayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xs_looktimes_permeability_today', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('oid');
            $table->string('belong', 20);
            $table->integer('bnum');
            $table->integer('gnum');
            $table->integer('age10b');
            $table->integer('age10g');
            $table->integer('age18b');
            $table->integer('age18g');
            $table->integer('age30b');
            $table->integer('age30g');
            $table->integer('age40b');
            $table->integer('age40g');
            $table->integer('age60b');
            $table->integer('age60g');
            $table->integer('age61b');
            $table->integer('age61g');
            $table->timestamp('date');
            $table->index('oid');
            $table->index('belong');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xs_looktimes_permeability_today');
    }
}
