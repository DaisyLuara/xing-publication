<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToCompanyMediaGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_media_groups', static function (Blueprint $table) {
            $table->string('type', 20)->default('image')->comment('分组类型:image,video');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_media_groups', static function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
