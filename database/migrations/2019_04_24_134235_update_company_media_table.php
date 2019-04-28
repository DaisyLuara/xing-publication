<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCompanyMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('company_media', static function (Blueprint $table) {
            $table->smallInteger('status')->default(2)->comment('0:未通过,1:通过,2:待审核');
            $table->integer('audit_user_id')->nullable()->comment('审核人');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('company_media', static function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('audit_user_id');
        });
    }
}
