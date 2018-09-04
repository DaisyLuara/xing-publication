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
//            $table->renameColumn('playernum20', 'playernum15');
//            $table->renameColumn('playernum30', 'playernum21');
            $table->integer('oanum')->after('phonenum');
            $table->integer('phonetimes')->after('oanum');
            $table->integer('oatimes')->after('phonetimes');

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
//            $table->renameColumn('playernum15', 'playernum20');
//            $table->renameColumn('playernum21', 'playernum30');
            $table->dropColumn('oanum');
            $table->dropColumn('phonetimes');
            $table->dropColumn('oatimes');
        });
    }
}
