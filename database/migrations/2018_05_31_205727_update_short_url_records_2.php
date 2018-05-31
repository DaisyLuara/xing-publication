<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateShortUrlRecords2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('short_url_records', function (Blueprint $table) {
            $table->string('ua')->default('');
            $table->string('ip')->default('');
            $table->integer('third_id')->default(0);
            $table->string('face_id')->default('');
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
            $table->dropColumn('ua');
            $table->dropColumn('ip');
            $table->dropColumn('third_id');
            $table->dropColumn('face_id');
        });
    }
}
