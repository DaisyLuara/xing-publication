<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMallcooScoreHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mallcoo_score_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mallcoo_open_user_id');
            $table->foreign('mallcoo_open_user_id')->references('mallcoo_open_user_id')->on('third_party_users')->onDelete('cascade');
            $table->double('score');
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mallcoo_score_histories');
    }
}
