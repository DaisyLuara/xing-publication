<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyidToAvrOfficialMarketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ar')->table('avr_official_market', function (Blueprint $table) {
            $table->unsignedInteger('companyid')->nullable()->comment('商场所属公司id');
            $table->string('logo')->nullable()->comment('门店logo');
            $table->string('phone')->nullable()->comment('门店电话');
            $table->string('address')->nullable()->comment('门店地址');
            $table->text('description')->nullable()->comment('门店描述');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('ar')->table('avr_official_market', function (Blueprint $table) {
            $table->dropColumn('companyid');
            $table->dropColumn('logo');
            $table->dropColumn('phone');
            $table->dropColumn('address');
            $table->dropColumn('description');
        });
    }
}
