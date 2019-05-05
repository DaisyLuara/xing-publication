<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('confessions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('wx_user_id')->default(0);
            $table->string('z')->default('');
            $table->string('name')->default('')->comment('告白对象姓名');
            $table->string('phone')->default('')->index();
            $table->integer('media_id')->nullable()->comment('照片');
            $table->text('message')->comment('告白留言');
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
        Schema::dropIfExists('confessions');
    }
}
