<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWxWarnings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wx_warnings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message')->default('');
            $table->string('project')->default('')->comment('节目名称');
            $table->integer('oid')->default(0)->comment('点位ID');
            $table->enum('type',[1,2])->default(1)->comment('1 error, 2 reset');
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
        Schema::dropIfExists('wx_warnings');
    }
}
