<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Warehouse\V1\Models\Location;

class ErpLocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Location::query()->create([
            'name' => '供应商',
            'warehouse_id' => '1'
        ]);
        Location::query()->create([
            'name' => '商场',
            'warehouse_id' => '1'
        ]);
    }
}
