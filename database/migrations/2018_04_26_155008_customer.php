<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Customer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('name', 1024)->defualt('')->comment('公司全称');
            $table->string('address', 1024)->defualt('')->comment('公司地址');
            $table->string('phone')->nullable()->unique();
            $table->string('email')->default('');
            $table->enum('status', [1, 2, 3, 4])->default(1)->comment('1待合作 2合作中 3已结束 4暂停');
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
        Schema::dropIfExists('customers');
    }
}
