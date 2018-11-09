<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentPayeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_payees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name')->comment('收款人姓名');
            $table->string('account_bank')->comment('收款人开户行');
            $table->string('account_number')->comment('收款人开户账号');
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
        Schema::dropIfExists('payment_payees');
    }
}
