<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaxScoreAndMinScoreToPolicy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupon_batch_policy', function (Blueprint $table) {
            $table->integer('min_score')->default(0);
            $table->integer('max_score')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupon_batch_policy', function (Blueprint $table) {
            if (Schema::hasColumn('coupon_batch_policy', 'min_score')) {
                $table->dropColumn('min_score');
            }

            if (Schema::hasColumn('coupon_batch_policy', 'max_score')) {
                $table->dropColumn('max_score');
            }
        });
    }
}
