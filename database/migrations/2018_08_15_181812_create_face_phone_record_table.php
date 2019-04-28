<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Controllers\Admin\Face\V1\Models\FacePhoneRecord;

class CreateFacePhoneRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('face_phone_records', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('date')->nullable();
            $table->timestamps();
        });
        FacePhoneRecord::create(['date' => '2017-04-21']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('face_phone_records');
    }
}
