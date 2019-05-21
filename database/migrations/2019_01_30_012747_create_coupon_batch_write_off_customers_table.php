<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponBatchWriteOffCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_batch_write_off_customers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('coupon_batch_id');
            $table->foreign('coupon_batch_id')->references('id')->on('coupon_batches')->onDelete('cascade');
            $table->unsignedInteger('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupon_batch_write_off_customers');
    }
}
