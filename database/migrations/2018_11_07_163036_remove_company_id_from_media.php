<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveCompanyIdFromMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('media', function (Blueprint $table) {
            if (Schema::hasColumn('media', 'company_id')) {
                $table->dropColumn('company_id');
            }

            if (Schema::hasColumn('media', 'contract_id')) {
                $table->dropColumn('contract_id');
            }
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->integer('company_id')->nullable()->comment('公司');
            $table->integer('contract_id')->nullable()->comment('合同');
        });
    }
}
