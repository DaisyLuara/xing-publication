<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempCustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 20)->defualt('')->comment('姓名');
            $table->string('mobile', 20)->nullable()->unique();
            $table->string('address')->default('');
            $table->integer('age')->default(0);
            $table->integer('gender')->default(0)->comment('0');
            $table->integer('oid')->default(0);
            $table->string('belong', 32);
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
        Schema::dropIfExists('temp_customers');
    }
}
