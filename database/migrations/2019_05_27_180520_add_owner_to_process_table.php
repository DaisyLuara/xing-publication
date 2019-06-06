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

        Schema::table('contract_costs', function (Blueprint $table) {
            $table->dropColumn('applicant_id');
            $table->dropColumn('applicant_name');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->integer('owner')->default(0)->after('applicant')->comment('所属人');
        });

        Schema::table('invoice_companies', function (Blueprint $table) {
            $table->integer('owner')->default(0)->after('user_id')->comment('所属人');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->integer('owner')->default(0)->after('applicant')->comment('所属人');
        });

        Schema::table('payment_payees', function (Blueprint $table) {
            $table->integer('owner')->default(0)->after('user_id')->comment('所属人');
        });

        Schema::table('demand_applications', function (Blueprint $table) {
            $table->integer('owner')->default(0)->after('applicant_id')->comment('所属人');
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

        Schema::table('contract_costs', function (Blueprint $table) {
            $table->integer('applicant_id')->after('contract_id');
            $table->integer('applicant_name')->after('applicant_id');
        });


        Schema::table('invoices', function (Blueprint $table) {
            $table->integer('owner');
        });

        Schema::table('invoice_companies', function (Blueprint $table) {
            $table->dropColumn('owner');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('owner');
        });

        Schema::table('payment_payees', function (Blueprint $table) {
            $table->dropColumn('owner');
        });

        Schema::table('demand_applications', function (Blueprint $table) {
            $table->dropColumn('owner');
        });
    }
}
