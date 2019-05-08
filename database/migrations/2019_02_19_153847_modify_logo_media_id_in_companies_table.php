<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyLogoMediaIdInCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('logo_media_id');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->unsignedInteger('logo_media_id')->nullable()->comment('商户图片');
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
            $table->dropColumn('logo_media_id');
            $table->integer('logo_media_id')->default(0)->comment('商户资源');
        });
    }
}
