<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Http\Controllers\Admin\Company\V1\Models\Company;

class AddNewRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::updateOrCreate(['name' => 'market_owner'], ['name' => 'setting', 'display_name' => '配置']);
        Permission::updateOrCreate(['name' => 'team'], ['name' => 'team', 'display_name' => '团队']);
        Permission::updateOrCreate(['name' => 'report'], ['name' => 'report', 'display_name' => '数据']);
        Permission::updateOrCreate(['name' => 'home'], ['name' => 'home', 'display_name' => '主页']);

        $roles = Role::get();
        $roles->each(function ($role) {

            if (!$role->hasAllPermissions(['team', 'report', 'home']) && in_array($role->name, ['super-admin', 'admin'])) {
                $role->givePermissionTo(['team', 'report', 'home']);
            }

            if (!$role->hasAllPermissions(['report', 'home']) && in_array($role->name, ['project-manager', 'user', 'ad_manager'])) {
                $role->givePermissionTo(['report', 'home']);
            }


        });

        $marketOwner = Role::updateOrCreate(['name' => 'market_owner'], ['name' => 'market_owner', 'display_name' => '场地主']);
        if (!$marketOwner->hasAllPermissions(['setting'])) {
            $marketOwner->givePermissionTo(['setting']);
        }


        $user = User::updateOrCreate(['phone' => '‭18795634433‬'], [
            'name' => '孙笑冬',
            'phone' => '‭18795634433‬',
            'password' => bcrypt('password'),
        ]);

        if ($user->hasRole('market_owner')) {
            $user->assignRole('market_owner');
        }

        Company::updateOrCreate(['name' => '华地百货'], [
            'user_id' => $user->id,
            'name' => '华地百货',
            'address' => '江苏省无锡市县前东街1号无锡金陵大饭店26层',
            'status' => 3,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
