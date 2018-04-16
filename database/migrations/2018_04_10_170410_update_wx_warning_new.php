<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWxWarningNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wx_warnings',function(Blueprint $table){
            $table->string('address',255)->comment('地址')->default('');
            $table->string('reason',255)->comment('原因')->default('');
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
            $table->dropColumn('address');
            $table->dropColumn('reason');
        });
    }
}
