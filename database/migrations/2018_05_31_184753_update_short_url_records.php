<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateShortUrlRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('short_url_records', function (Blueprint $table) {
            $table->string('utm_source')->default('');
            $table->string('utm_medium')->default('');
            $table->string('utm_term')->default('');
            $table->string('utm_campaign')->default('');
            $table->string('utm_content')->default('');
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
            $table->dropColumn('utm_source');
            $table->dropColumn('utm_medium');
            $table->dropColumn('utm_term');
            $table->dropColumn('utm_campaign');
            $table->dropColumn('utm_content');
        });
    }
}
