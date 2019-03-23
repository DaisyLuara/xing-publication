<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreateCustomerIdToCouponBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_batches', function (Blueprint $table) {
            $table->unsignedInteger('create_customer_id')->after('create_user_id')->default(0)->comment('创建客户id');
        });

        Schema::table('policies', function (Blueprint $table) {
            $table->unsignedInteger('create_customer_id')->after('create_user_id')->default(0)->comment('创建客户id');
        });

        DB::connection('mysql')->statement('alter table coupon_batches modify create_user_id int unsigned default 0');

        DB::connection('mysql')->statement('alter table policies modify create_user_id int unsigned default 0');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupon_batches', function (Blueprint $table) {
            $table->dropColumn('create_customer_id');
        });

        Schema::table('policies', function (Blueprint $table) {
            $table->dropColumn('create_customer_id');
        });

        DB::connection('mysql')->statement('alter table coupon_batches modify create_user_id int unsigned');

        DB::connection('mysql')->statement('alter table policies modify create_user_id int unsigned');


    }
}
