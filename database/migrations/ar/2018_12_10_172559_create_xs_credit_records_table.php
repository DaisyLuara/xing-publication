<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXsCreditRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('ar')->hasTable('xs_credit_records')) {
            Schema::connection('ar')->create('xs_credit_records', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('uid');
                $table->unsignedInteger('num')->comment('消费积分');
                $table->string('key')->comment('标识');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xs_credit_records');
    }
}
