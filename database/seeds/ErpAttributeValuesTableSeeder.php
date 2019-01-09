<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Warehouse\V1\Models\AttributeValue;

class ErpAttributeValuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
