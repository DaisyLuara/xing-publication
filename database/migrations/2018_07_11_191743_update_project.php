<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ar')->table('ar_product_list', function (Blueprint $table) {
            if (!Schema::hasColumn('ar_product_list', 'policy_id')) {
                $table->integer('policy_id')->default(0)->nullable()->comment('优惠券投放策略主键');
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
        Schema::connection('ar')->table('ar_product_list', function (Blueprint $table) {
            if (Schema::hasColumn('ar_product_list', 'policy_id')) {
                $table->dropColumn('policy_id');
            }
        });
    }
}
