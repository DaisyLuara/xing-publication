<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Warehouse\V1\Models\Attribute;
use App\Http\Controllers\Admin\Warehouse\V1\Models\Location;
use App\Http\Controllers\Admin\Warehouse\V1\Models\Warehouse;
use App\Http\Controllers\Admin\Warehouse\V1\Models\AttributeValue;

class ErpAttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Attribute::query()->create([
            'name' => 'name',
            'display_name' => '产品名称'
        ]);
        Attribute::query()->create([
            'name' => 'color',
            'display_name' => '产品颜色'
        ]);
        Location::query()->create([
            'name' => '供应商',
            'warehouse_id' => '1'
        ]);
        Location::query()->create([
            'name' => '商场',
            'warehouse_id' => '1'

        ]);
        Warehouse::query()->create([
            'name' => '虚拟仓库',
            'address' => '虚拟仓库'
        ]);

        if (env('APP_ENV') = 'develop') {
            AttributeValue::query()->create([
                'attribute_id' => '1',
                'value' => '测试产品名称1'
            ]);
            AttributeValue::query()->create([
                'attribute_id' => '1',
                'value' => '测试产品名称2'
            ]);
            AttributeValue::query()->create([
                'attribute_id' => '1',
                'value' => '测试产品名称3'
            ]);
            AttributeValue::query()->create([
                'attribute_id' => '2',
                'value' => '红色'
            ]);
            AttributeValue::query()->create([
                'attribute_id' => '2',
                'value' => '黄色'
            ]);
            AttributeValue::query()->create([
                'attribute_id' => '2',
                'value' => '蓝色'
            ]);
        }

    }
}
