<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_config', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id')->nullable()->comment('商场所属公司id');
            $table->unsignedInteger('bd_user_id')->nullable()->comment('所属BD');
            $table->unsignedInteger('contract_id')->nullable()->comment('合同ID');
            $table->unsignedInteger('media_id')->nullable()->comment('商场logo图片ID');
            $table->string('phone')->nullable()->comment('商场电话');
            $table->string('address')->nullable()->comment('商场地址');
            $table->text('description')->nullable()->comment('商场描述');
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
        Schema::dropIfExists('market_config');
    }
}
