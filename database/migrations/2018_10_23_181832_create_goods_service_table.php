<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Controllers\Admin\Invoice\V1\Models\GoodsService;

class CreateGoodsServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('货物或服务名称');
            $table->string('spec_type')->comment('规格型号');
            $table->string('unit')->comment('单位');
            $table->timestamps();
        });
        $data = [
            [
                'name' => '星视度智能互动屏',
                'spec_type' => '无',
                'unit' => '台',
            ],
            [
                'name' => '*软件*星视度智能互动软件V1.0',
                'spec_type' => '无',
                'unit' => '套',
            ],
            [
                'name' => '*信息技术服务*星视度智能互动软件V1.0服务费',
                'spec_type' => '无',
                'unit' => '套',
            ],
        ];
        foreach ($data as $item) {
            GoodsService::create($item);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_services');
    }
}
