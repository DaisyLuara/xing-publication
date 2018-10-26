<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateXsPointAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ar')->table('xs_point_attributes', function (Blueprint $table) {
            $table->dropUnique(['attribute_id', 'point_id']);
            $table->dropIndex(['attribute_id','point_id']);
            $table->index('attribute_id');
            $table->index('point_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('ar')->table('xs_point_attributes', function (Blueprint $table) {
            $table->dropIndex('attribute_id');
            $table->dropIndex('point_id');
            $table->index(['attribute_id', 'point_id']);
            $table->unique(['attribute_id', 'point_id']);
        });
    }
}
