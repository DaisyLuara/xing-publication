<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_id');
            $table->integer('goods_service_id');
            $table->integer('num')->comment('数量');
            $table->string('price')->comment('单价');
            $table->string('money')->comment('金额');
            $table->index('invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_contents');
    }
}
