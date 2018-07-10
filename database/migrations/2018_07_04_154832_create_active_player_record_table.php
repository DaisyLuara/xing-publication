<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Controllers\Admin\Face\V1\Models\ActivePlayerRecord;

class CreateActivePlayerRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('face_active_player_records', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('date')->nullable();
        });
        DB::table('face_active_player_records')->insert(['date' => '2018-06-13']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('face_active_player_records');
    }
}
