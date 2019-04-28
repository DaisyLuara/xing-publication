<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateXsFaceActivePlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ar')->table('xs_face_active_player', function (Blueprint $table) {
            $table->renameColumn('playernum20', 'playernum15');
            $table->renameColumn('playernum30', 'playernum21');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('ar')->table('xs_face_active_player', function (Blueprint $table) {
            $table->renameColumn('playernum15', 'playernum20');
            $table->renameColumn('playernum21', 'playernum30');
        });
    }
}
