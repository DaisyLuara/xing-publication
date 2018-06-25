<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Controllers\Admin\Face\V1\Models\FacePeopleTimeRecord;

class CreateFacePeopleTimeRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('face_people_time_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('max_id');
            $table->timestamps();
        });
        FacePeopleTimeRecord::create(['max_id' => 0]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('face_people_time_records');
    }
}
