<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Privilege\V1\Models\Permission;

class UpdatePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permission::all();
        Permission::query()->delete();
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission->name, 'display_name' => $permission->display_name]);
        }
    }
}
