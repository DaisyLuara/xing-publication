<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->enum('type', [1, 2])->default(1)->comment('1:自营, 2:连锁');
            $table->unsignedInteger('marketid')->nullable()->comment('场地ID');
            $table->unsignedInteger('areaid')->comment('区域ID');
            $table->unsignedInteger('user_id')->nullable()->comment('所属BD');
            $table->unsignedInteger('contract_id')->nullable()->comment('合同ID');
            $table->string('name')->comment('门店名称');
            $table->string('logo')->nullable()->comment('门店logo');
            $table->string('phone')->nullable()->comment('门店电话');
            $table->string('address')->nullable()->comment('门店地址');
            $table->text('description')->nullable()->comment('门店描述');
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
        Schema::dropIfExists('stores');
    }
}
