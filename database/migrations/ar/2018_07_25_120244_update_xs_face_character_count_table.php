<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateXsFaceCharacterCountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ar')->table('xs_face_character_count', function (Blueprint $table) {
            $table->renameColumn('century0_bnum', 'century10_bnum');
            $table->renameColumn('century0_gnum', 'century10_gnum');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('ar')->table('xs_face_character_count', function (Blueprint $table) {
            $table->renameColumn('century10_bnum', 'century0_bnum');
            $table->renameColumn('century10_gnum', 'century0_gnum');
        });
    }
}
