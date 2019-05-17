<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBoardsAddMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('boards', static function (Blueprint $table) {
            $table->string('message')->after('id')->default('');
            $table->integer('activity_media_id')->after('id')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activities', static function (Blueprint $table) {
            $table->dropColumn('message');
            $table->dropColumn('activity_media_id');
        });
    }
}
