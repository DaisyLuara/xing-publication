<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Warehouse\V1\Models\Attribute;

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
    }
}
