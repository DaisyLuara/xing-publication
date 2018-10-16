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
            $table->string('spec_type');
            $table->string('unit');
            $table->integer('num');
            $table->integer('price');
            $table->integer('total');
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
