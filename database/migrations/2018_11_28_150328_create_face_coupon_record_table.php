<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Controllers\Admin\Face\V1\Models\FaceCouponRecord;

class CreateFaceCouponRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('face_coupon_records', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('date')->nullable();
            $table->timestamps();
        });

        FaceCouponRecord::create(['date'=>'2018-11-15']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('face_coupon_record');
    }
}
