<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Controllers\Admin\Demand\V1\Models\DemandModify;
use Illuminate\Support\Facades\DB;

class ChangeReviewTimeToDemandModifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DemandModify::query()->update(['review_time' => null]);

        DB::statement("ALTER TABLE demand_modifies MODIFY COLUMN review_time timestamp NULL DEFAULT NULL COMMENT '反馈时间' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DemandModify::query()->update(['review_time' => null]);

        DB::statement("ALTER TABLE demand_modifies MODIFY COLUMN review_time int(11) NULL DEFAULT NULL COMMENT '反馈时间' ");

    }
}
