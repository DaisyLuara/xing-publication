<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Contract\V1\Models\ContractCostKind;
use App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttributeValue;

class AddCostKindSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ContractCostKind::query()->where('name', '物流费用')->update(['alias' => 'transport']);
        ContractCostKind::query()->where('name', '运维费用')->update(['alias' => 'operation', 'default_cost' => 1000]);
        ContractCostKind::query()->where('name', '4G网络费用')->update(['alias' => 'network', 'default_cost' => 500]);
        ContractCostKind::query()->where('name', '人员差旅')->update(['alias' => 'travel']);
        ContractCostKind::query()->where('name', '物料费用')->update(['alias' => 'materiel']);
        ContractCostKind::query()->where('name', '其他')->update(['alias' => 'other']);

        ContractCostKind::create(['alias' => 'hardware', 'name' => '硬件费用']);
        ContractCostKind::create(['alias' => 'discount', 'name' => '公司优惠']);

        ErpAttributeValue::create(['attribute_id' => 1, 'value' => '1.0版智能互动盒子']);
    }
}
