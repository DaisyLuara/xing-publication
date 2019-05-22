<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RecreateCompanyMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('company_media');
        Schema::create('company_media', static function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id');
            $table->integer('media_id');
            $table->smallInteger('status');
            $table->integer('audit_user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_media');
        Schema::create('company_media', static function (Blueprint $table) {
            $table->integer('company_id');
            $table->integer('media_id');
            $table->smallInteger('status')->comment('0:未通过,1:通过,2:待审核');
            $table->integer('audit_user_id')->nullable();
        });
    }
}
