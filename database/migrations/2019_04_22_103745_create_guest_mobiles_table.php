<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuestMobilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('guest_mobiles', static function (Blueprint $table) {
            $table->increments('id');
            $table->string('mobile')->comment('游客手机号');
            $table->string('ip')->nullable()->comment('ip地址');
            $table->string('city')->nullable()->comment('所在城市');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_mobiles');
    }
}
