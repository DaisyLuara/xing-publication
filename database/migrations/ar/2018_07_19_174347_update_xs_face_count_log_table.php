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
            $table->integer('omo_outnum')->after('scannum');
            $table->integer('omo_scannum')->after('omo_outnum');
            $table->integer('omo_sharenum')->after('omo_scannum');
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
            $table->dropColumn('omo_outnum');
            $table->dropColumn('omo_scannum');
            $table->dropColumn('omo_sharenum');
        });
    }
}
