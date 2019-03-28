<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDataRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('face_character_records', function (Blueprint $table) {
            $table->rename('face_looknum_character_records');
        });

        Schema::table('face_log_records', function (Blueprint $table) {
            $table->rename('face_looknum_permeability_records');
        });

        Schema::table('face_character_times_records', function (Blueprint $table) {
            $table->rename('face_looktimes_character_records');
        });

        Schema::table('face_log_times_records', function (Blueprint $table) {
            $table->rename('face_looktimes_permeability_records');
        });

        Schema::table('face_play_character_records', function (Blueprint $table) {
            $table->rename('face_playtimes_character_records');
        });

        Schema::table('face_permeability_records', function (Blueprint $table) {
            $table->rename('face_playtimes_permeability_records');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('face_looknum_character_records', function (Blueprint $table) {
            $table->rename('face_character_records');
        });

        Schema::table('face_looknum_permeability_records', function (Blueprint $table) {
            $table->rename('face_log_records');
        });

        Schema::table('face_looktimes_character_records', function (Blueprint $table) {
            $table->rename('face_character_times_records');
        });

        Schema::table('face_looktimes_permeability_records', function (Blueprint $table) {
            $table->rename('face_log_times_records');
        });

        Schema::table('face_playtimes_character_records', function (Blueprint $table) {
            $table->rename('face_play_character_records');
        });

        Schema::table('face_playtimes_permeability_records', function (Blueprint $table) {
            $table->rename('face_permeability_records');
        });
    }
}
