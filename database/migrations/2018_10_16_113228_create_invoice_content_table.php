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
            $table->string('name');
            $table->string('spec_type')->comment('规格型号');
            $table->string('unit')->comment('单位');
            $table->integer('num')->comment('数量');
            $table->integer('price')->comment('单价');
            $table->integer('total')->comment('总计');
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
