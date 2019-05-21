<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToMallcooScoreHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mallcoo_score_histories', function (Blueprint $table) {
            $table->enum('type', ['increase', 'decrease'])->index();
            $table->dropForeign('mallcoo_score_histories_mallcoo_open_user_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mallcoo_score_histories', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
