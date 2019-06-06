<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultValueToCostKind extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_cost_kinds', function (Blueprint $table) {
            $table->string('alias')->nullable()->after('id')->comment('别名');
            $table->integer('default_cost')->default(0)->after('name')->comment('默认');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_cost_kinds', function (Blueprint $table) {
            $table->dropColumn('alias');
            $table->dropColumn('default_cost');
        });
    }
}
