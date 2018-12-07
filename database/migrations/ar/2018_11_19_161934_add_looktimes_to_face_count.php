<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLooktimesToFaceCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ar')->table('xs_face_count_log', function (Blueprint $table) {
            $table->integer('looktimes')->after('oatimes');
            $table->integer('lovetimes')->after('playtimes21');
            $table->integer('verifytimes')->after('lovetimes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('ar')->table('xs_face_count_log', function (Blueprint $table) {
            $table->dropColumn('looktimes');
            $table->dropColumn('lovetimes');
            $table->dropColumn('verifytimes');
        });
    }
}
