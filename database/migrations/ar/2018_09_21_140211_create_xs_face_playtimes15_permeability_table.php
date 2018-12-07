<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXsFacePlaytimes15PermeabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ar')->create('xs_face_playtimes15_permeability', function (Blueprint $table) {
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
        Schema::connection('ar')->dropIfExists('xs_face_playtimes15_permeability');
    }
}
