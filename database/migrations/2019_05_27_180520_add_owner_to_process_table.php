<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOwnerToProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->integer('owner')->default(0)->after('applicant')->comment('所属人');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->integer('owner')->default(0)->after('applicant')->comment('所属人');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->integer('owner')->default(0)->after('applicant')->comment('所属人');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('owner');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('owner');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('owner');
        });
    }
}
