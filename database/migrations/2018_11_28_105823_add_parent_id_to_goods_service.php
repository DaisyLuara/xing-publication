<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Http\Controllers\Admin\Invoice\V1\Models\GoodsService;

class AddParentIdToGoodsService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('goods_services', function (Blueprint $table) {
            $table->integer('invoice_kind_id')->after('id');
        });

        $data1 = [
            [
                'name' => '43寸立式双屏室内机',
            ],
            [
                'name' => '32寸双屏立式室内机',
            ],
            [
                'name' => '43寸立式室内机',
            ],
            [
                'name' => '一体机',
            ],
            [
                'name' => '43寸壁挂式一体机',
            ],
            [
                'name' => '70寸箱体式镜面一体机定制款',
            ],
            [
                'name' => '32寸壁挂式镜面一体机定制',
            ],
            [
                'name' => '星视度智能互动一体机',
            ],
        ];
        foreach ($data1 as $item) {
            GoodsService::create(array_merge($item, ['invoice_kind_id' => 1, 'spec_type' => '无', 'unit' => '台']));
        }

        $data2 = [
            [
                'name' => '节目定制：*软件*星视度智能互动软件V1.0'
            ],
            [
                'name' => '体验营销：*软件*星视度管理软件V1.0'
            ],
        ];
        foreach ($data2 as $item) {
            GoodsService::create(array_merge($item, ['invoice_kind_id' => 2, 'spec_type' => '无', 'unit' => '套']));
        }

        $data3 = [
            [
                'name' => '节目定制：*信息技术服务*星视度智能互动软件V1.0服务费',
            ],
            [
                'name' => '活动租赁：*信息技术服务*星视度智能互动软件V1.0服务费',
            ],
            [
                'name' => '体验营销：*信息技术服务*星视度智能互动软件V1.0服务费',
            ]
        ];
        foreach ($data3 as $item) {
            GoodsService::create(array_merge($item, ['invoice_kind_id' => 3, 'spec_type' => '无', 'unit' => '套']));
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('goods_services', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });
    }
}
