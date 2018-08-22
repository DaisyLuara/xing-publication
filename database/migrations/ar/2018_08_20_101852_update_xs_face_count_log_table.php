<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateXsFaceCountLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ar')->table('xs_face_count_log', function (Blueprint $table) {
            $table->integer('playtimes7')->after('phonenum');
            $table->integer('playtimes15')->after('playtimes7');
            $table->integer('playtimes21')->after('playtimes15');
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
            $table->dropColumn('playtimes7');
            $table->dropColumn('playtimes15');
            $table->dropColumn('playtimes21');
        });
    }
}
