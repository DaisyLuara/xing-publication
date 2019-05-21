<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAge50ToPermeability extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('xs_looktimes_permeability_today', function (Blueprint $table) {
            $table->integer('age50b')->after('age40g');
            $table->integer('age50g')->after('age50b');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permeability', function (Blueprint $table) {
            $table->dropColumn('age50b');
            $table->dropColumn('age50g');
        });
    }
}
