<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateShortUrlRecords3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('short_url_records', function (Blueprint $table) {
            $table->string('browser')->default('');
            $table->string('device')->default('');
            $table->string('platform')->default('');
            $table->string('app')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('short_url_records', function (Blueprint $table) {
            if (Schema::hasColumn('short_url_records', 'browser')) {
                $table->dropColumn('browser');
            }
            if (Schema::hasColumn('short_url_records', 'device')) {
                $table->dropColumn('device');
            }
            if (Schema::hasColumn('short_url_records', 'platform')) {
                $table->dropColumn('platform');
            }
            if (Schema::hasColumn('short_url_records', 'app')) {
                $table->dropColumn('app');
            }
        });
    }
}
