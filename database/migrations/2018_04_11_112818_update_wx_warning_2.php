<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWxWarning2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wx_warnings', function (Blueprint $table) {
            $table->integer('product_id')->comment('节目ID')->default(0);
            $table->integer('market_id')->comment('商场ID')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wx_warnings', function (Blueprint $table) {
            $table->dropColumn('product_id');
            $table->dropColumn('market_id');
        });
    }
}
