<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PointAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_attributes', function (Blueprint $table) {
            $table->integer('attribute_id');
            $table->integer('point_id');
            $table->index(['attribute_id', 'point_id']);
            $table->unique(['attribute_id', 'point_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('point_attributes');
    }
}
