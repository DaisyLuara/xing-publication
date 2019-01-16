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
        AttributeValue::query()->create([
            'attribute_id' => '1',
            'value' => '2.0版单屏机器'
        ]);
        AttributeValue::query()->create([
            'attribute_id' => '1',
            'value' => '2.5版单屏机器'
        ]);
        AttributeValue::query()->create([
            'attribute_id' => '1',
            'value' => '3.0版小S双屏机器'
        ]);
        AttributeValue::query()->create([
            'attribute_id' => '1',
            'value' => '3.0版大K双屏机器'
        ]);
        AttributeValue::query()->create([
            'attribute_id' => '1',
            'value' => '2.5版单屏机器'
        ]);
        AttributeValue::query()->create([
            'attribute_id' => '1',
            'value' => '1.0版镜面屏一体机'
        ]);
        AttributeValue::query()->create([
            'attribute_id' => '1',
            'value' => '1.0版43寸大平板'
        ]);
        AttributeValue::query()->create([
            'attribute_id' => '1',
            'value' => '1.0版43寸大平板'
        ]);
        AttributeValue::query()->create([
            'attribute_id' => '1',
            'value' => '3.0版大K双屏样机'
        ]);
        AttributeValue::query()->create([
            'attribute_id' => '2',
            'value' => '红+白'
        ]);
        AttributeValue::query()->create([
            'attribute_id' => '2',
            'value' => '红色'
        ]);
        AttributeValue::query()->create([
            'attribute_id' => '2',
            'value' => '白色'
        ]);
        AttributeValue::query()->create([
            'attribute_id' => '2',
            'value' => '银色'
        ]);
        AttributeValue::query()->create([
            'attribute_id' => '2',
            'value' => '香槟金'
        ]);
        AttributeValue::query()->create([
            'attribute_id' => '2',
            'value' => '黑色'
        ]);
    }
}
