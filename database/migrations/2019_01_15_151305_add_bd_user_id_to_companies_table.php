<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBdUserIdToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->unsignedInteger('bd_user_id')->after('trade_id')->comment('所属BD');
            $table->unsignedInteger('parent_id')->nullable()->after('address');
            $table->foreign('parent_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('bd_user_id');
            $table->dropForeign('companies_parent_id_foreign');
            $table->dropColumn('parent_id');
        });
    }
}
