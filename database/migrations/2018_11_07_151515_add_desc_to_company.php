<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescToCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->text('description')->nullable()->comment('商户描述');
            $table->string('logo')->default('')->comment('商户logo');
            $table->integer('logo_media_id')->default(0)->comment('商户资源');
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
            if (Schema::hasColumn('companies', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('companies', 'logo')) {
                $table->dropColumn('logo');
            }
            if (Schema::hasColumn('companies', 'logo_media_id')) {
                $table->dropColumn('logo_media_id');
            }
        });
    }
}
