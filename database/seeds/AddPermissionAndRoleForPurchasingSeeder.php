<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class AddPermissionAndRoleForPurchasingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::query()->updateOrCreate(['name' => 'purchasing'], ['name' => 'purchasing', 'display_name' => '采购库存']);
        $purchasing = Role::query()->updateOrCreate(['name' => 'purchasing'], ['name' => 'purchasing', 'display_name' => '采购']);
        $purchasing->givePermissionTo(['contract', 'purchasing', 'company']);
    }
}
