<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlaytimes15ToXsFaceCountToday extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('xs_face_count_today', function (Blueprint $table) {
            $table->integer('playtimes15')->after('playtimes7');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('xs_face_count_today', function (Blueprint $table) {
            $table->dropColumn('playtimes15');
        });
    }
}
