<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_relationships', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("contract_id");
            $table->morphs("association");
            $table->string("type")->nullable()->comment("类型，预留字段");
            $table->string("remark")->nullable()->comment("备注，预留字段");
            $table->text("data")->nullable()->comment("数据，预留字段");
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
        Schema::dropIfExists('contract_relationships');
    }
}
