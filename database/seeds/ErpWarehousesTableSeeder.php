<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Warehouse\V1\Models\Warehouse;

class ErpWarehousesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Warehouse::query()->create([
            'name' => '虚拟库位',
            'address' => '虚拟库位'
        ]);
    }
}
