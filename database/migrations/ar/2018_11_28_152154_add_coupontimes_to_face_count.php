<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoupontimesToFaceCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ar')->table('xs_face_count_log', function (Blueprint $table) {
            $table->integer('coupontimes')->after('lovetimes');
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
            $table->dropColumn('coupontimes');
        });
    }
}
