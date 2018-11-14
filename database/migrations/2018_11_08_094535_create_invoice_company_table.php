<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_companies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name')->comment('开票公司名称');
            $table->string('taxpayer_num')->comment('纳税人识别号');
            $table->string('phone')->nullable()->comment('手机');
            $table->string('telephone')->nullable()->comment('固定电话');
            $table->string('address')->comment('地址');
            $table->string('account_bank')->comment('开户行');
            $table->string('account_number')->comment('开户账号');
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
        Schema::dropIfExists('invoice_companies');
    }
}
