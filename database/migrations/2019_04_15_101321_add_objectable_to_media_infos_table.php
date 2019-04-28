<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddObjectableToMediaInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('media_infos', function (Blueprint $table) {
            $table->string('objectable_type')->nullable();
            $table->integer('objectable_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('media_infos', function (Blueprint $table) {
            $table->dropColumn(["objectable_type", "objectable_id"]);
        });
    }
}
