<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRecordIdAndBelongToConfessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('confessions', function (Blueprint $table) {
            $table->integer('qiniu_id')->nullable()->after('phone')->comment('七牛ID');
            $table->string('record_id')->nullable()->after('media_id')->comment('录音ID');
            $table->string('utm_campaign')->default('')->after('message')->comment('游戏名称');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('confessions', function (Blueprint $table) {
            $table->dropColumn('qiniu_id');
            $table->dropColumn('record_id');
            $table->dropColumn('utm_campaign');
        });
    }
}
