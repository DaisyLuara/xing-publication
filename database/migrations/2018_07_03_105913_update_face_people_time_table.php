<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFacePeopleTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ar')->table('face_people_time', function (Blueprint $table) {
            //
            if (!collect(DB::connection('ar')->select("SHOW INDEXES FROM face_people_time"))->pluck('clientdate')) {
                $table->index('clientdate');
            }

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('ar')->table('face_people_time', function (Blueprint $table) {
            $table->dropIndex(['clientdate']);
        });
    }
}
