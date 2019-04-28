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
        if (!Schema::connection('ar')->hasTable('xs_point_attributes')) {
            Schema::connection('ar')->create('xs_point_attributes', function (Blueprint $table) {
                $table->integer('attribute_id')->comment('属性主键');
                $table->integer('point_id')->comment('点位主键');
                $table->index(['attribute_id', 'point_id']);
                $table->unique(['attribute_id', 'point_id']);
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
        Schema::connection('ar')->dropIfExists('xs_point_attributes');
    }
}
